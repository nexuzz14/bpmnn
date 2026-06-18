<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\DrafSurat;
use App\Models\ReviuSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DrafSayaController extends Controller
{
    public function index(Request $request)
    {
        // Draf Surat yang sudah dibuat
        $drafQuery = DrafSurat::with(['suratMasuk', 'reviuSurat'])->where('dibuat_oleh', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            $drafQuery->whereHas('suratMasuk', function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua Status' && $request->status !== '') {
            $drafQuery->where('status', strtolower(str_replace(' ', '_', $request->status)));
        }
            
        $drafSurats = $drafQuery->latest()->paginate(10, ['*'], 'draf_page')->withQueryString();
            
        $userId = auth()->id();
        $totalDrafCount = DrafSurat::where('dibuat_oleh', $userId)->count();

        $latestReviewsSubquery = DB::table('reviu_surats')
            ->select(DB::raw('MAX(reviu_surats.id) as id'))
            ->join('draf_surats', 'draf_surats.id', '=', 'reviu_surats.draf_surat_id')
            ->where('draf_surats.dibuat_oleh', $userId)
            ->groupBy('draf_surat_id');

        $latestReviews = ReviuSurat::whereIn('id', $latestReviewsSubquery)
            ->select('status', 'tingkat', DB::raw('count(*) as count'))
            ->groupBy('status', 'tingkat')
            ->get();

        $stats = [
            'total' => $totalDrafCount,
            'menunggu' => 0,
            'disetujui' => 0,
            'revisi' => 0
        ];

        foreach ($latestReviews as $rev) {
            if ($rev->status === 'menunggu') {
                $stats['menunggu'] += $rev->count;
            } elseif ($rev->status === 'disetujui' && $rev->tingkat == 'final') {
                $stats['disetujui'] += $rev->count;
            } elseif ($rev->status === 'revisi') {
                $stats['revisi'] += $rev->count;
            }
        }

        return view('kabag.draf-saya.index', compact('drafSurats', 'stats'));
    }

    public function create(Request $request)
    {
        $templates = \App\Models\TemplateSurat::latest()->get();
        $suratMasuks = \App\Models\SuratMasuk::latest()->get();
        
        return view('kabag.draf-saya.create', compact('suratMasuks', 'templates'));
    }

    public function downloadTemplate($id)
    {
        $template = \App\Models\TemplateSurat::findOrFail($id);
        
        if (!Storage::disk('public')->exists($template->file_path)) {
            return back()->with('error', 'File template tidak ditemukan.');
        }

        return Storage::disk('public')->download($template->file_path, $template->nama_template . '.' . pathinfo($template->file_path, PATHINFO_EXTENSION));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'surat_masuk_id' => ['required', 'exists:surat_masuks,id'],
            'file_draf'      => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        try {
            DB::transaction(function() use ($request, $validated) {
                $path = $request->file('file_draf')->store('draf-surat', 'public');

                $draf = DrafSurat::create([
                    'surat_masuk_id' => $validated['surat_masuk_id'],
                    'file_draf'      => $path,
                    'status'         => 'menunggu_reviu',
                    'dibuat_oleh'    => auth()->id()
                ]);

                $kabiro = \App\Models\User::where('role', 'kepala_biro')->first();

                // Buat Reviu Tingkat Final (Kabiro)
                ReviuSurat::create([
                    'draf_surat_id' => $draf->id,
                    'tingkat'       => 'final',
                    'status'        => 'menunggu',
                    'reviewer_id'   => $kabiro ? $kabiro->id : null
                ]);

                if ($kabiro) {
                    $kabiro->notify(new \App\Notifications\SuratNotification(
                        'Persetujuan Final Draf',
                        "Kabag telah mengunggah draf surat baru dan menunggu persetujuan akhir.",
                        route('kabiro.review-final.index')
                    ));
                }
            });

            return redirect()->route('kabag.draf-saya.index')->with('success', 'Draf surat berhasil diunggah dan dikirim untuk reviu Kabiro.');

        } catch (\Exception $e) {
            \Log::error('Gagal upload draf surat: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan draf surat: ' . $e->getMessage());
        }
    }

    public function show(DrafSurat $drafSurat)
    {
        abort_if($drafSurat->dibuat_oleh !== auth()->id(), 403, 'Anda tidak diizinkan mengakses draft ini.');

        $drafSurat->load(['suratMasuk', 'reviuSurat' => function($q) {
            $q->orderBy('created_at', 'asc');
        }]);

        $penugasan = Disposisi::with('dariUser')
            ->where('surat_masuk_id', $drafSurat->surat_masuk_id)
            ->where('ke_user_id', $drafSurat->dibuat_oleh)
            ->first();

        $drafSurat->setRelation('penugasan', $penugasan);
        
        return view('kabag.draf-saya.show', compact('drafSurat'));
    }

    public function edit(DrafSurat $drafSurat)
    {
        abort_if($drafSurat->dibuat_oleh !== auth()->id(), 403, 'Anda tidak diizinkan mengubah draft ini.');

        return view('kabag.draf-saya.edit', compact('drafSurat'));
    }

    public function update(Request $request, DrafSurat $drafSurat)
    {
        abort_if($drafSurat->dibuat_oleh !== auth()->id(), 403, 'Anda tidak diizinkan memperbarui draft ini.');

        $validated = $request->validate([
            'file_draf' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        DB::transaction(function() use ($request, $drafSurat) {
            // Delete old file
            if ($drafSurat->file_draf) {
                Storage::disk('public')->delete($drafSurat->file_draf);
            }

            $path = $request->file('file_draf')->store('draf-surat', 'public');

            $drafSurat->update([
                'file_draf' => $path,
                'status' => 'menunggu_reviu' // Reset status
            ]);

            $kabiro = \App\Models\User::where('role', 'kepala_biro')->first();

            // Buat Reviu Tingkat Final (Kabiro)
            \App\Models\ReviuSurat::create([
                'draf_surat_id' => $drafSurat->id,
                'tingkat'       => 'final',
                'status' => 'menunggu',
                'reviewer_id' => $kabiro ? $kabiro->id : null
            ]);
        });

        return redirect()->route('kabag.draf-saya.index')->with('success', 'Draf surat revisi berhasil diunggah.');
    }

    public function destroy(DrafSurat $drafSurat)
    {
        abort_if($drafSurat->dibuat_oleh !== auth()->id(), 403, 'Anda tidak diizinkan menghapus draft ini.');

        // Only allow deletion if it hasn't been approved or is not done
        if ($drafSurat->status === 'selesai' || $drafSurat->reviuSurat()->where('status', 'disetujui')->exists()) {
            return redirect()->back()->with('error', 'Draft surat yang sudah disetujui atau selesai tidak dapat dihapus.');
        }

        DB::transaction(function() use ($drafSurat) {
            // Revert disposisi status if this is the only draft
            $penugasan = Disposisi::where('surat_masuk_id', $drafSurat->surat_masuk_id)
                ->where('ke_user_id', $drafSurat->dibuat_oleh)
                ->first();
                
            if ($penugasan) {
                // If there are no other drafts, revert status to dibaca
                $otherDrafts = DrafSurat::where('surat_masuk_id', $drafSurat->surat_masuk_id)
                    ->where('dibuat_oleh', $drafSurat->dibuat_oleh)
                    ->where('id', '!=', $drafSurat->id)
                    ->exists();
                    
                if (!$otherDrafts && $penugasan->status === 'ditindaklanjuti') {
                    $penugasan->update(['status' => 'dibaca']);
                }
            }

            if ($drafSurat->file_draf) {
                Storage::disk('public')->delete($drafSurat->file_draf);
            }
            $drafSurat->delete();
        });

        return redirect()->route('kabag.draf-saya.index')->with('success', 'Draft surat berhasil dihapus.');
    }
}
