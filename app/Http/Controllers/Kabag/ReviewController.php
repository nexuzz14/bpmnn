<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\ReviuSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ReviuSurat::with(['drafSurat.suratMasuk', 'drafSurat.pembuat'])
            ->where('tingkat', '2');

        if ($search = $request->query('search')) {
            $query->whereHas('drafSurat.suratMasuk', function($q) use ($search) {
                $q->where('perihal', 'like', "%{$search}%");
            })->orWhereHas('drafSurat', function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%");
            });
        }

        if ($kasubtim = $request->query('kasubtim')) {
            $query->whereHas('drafSurat', function($q) use ($kasubtim) {
                $q->whereExists(function($sub) use ($kasubtim) {
                    $sub->select(\Illuminate\Support\Facades\DB::raw(1))
                        ->from('disposisis')
                        ->whereColumn('disposisis.surat_masuk_id', 'draf_surats.surat_masuk_id')
                        ->whereColumn('disposisis.ke_user_id', 'draf_surats.dibuat_oleh')
                        ->where('disposisis.dari_user_id', $kasubtim);
                });
            });
        }

        $revius = $query->latest()->paginate(10)->withQueryString();
            
        // Get list of kasubtim that have submitted drafts for review
        $kasubtims = \App\Models\User::where('role', 'kasubtim')->get();
        
        return view('kabag.review.index', compact('revius', 'kasubtims'));
    }

    public function show(ReviuSurat $review)
    {
        return view('kabag.review.show', compact('review'));
    }

    public function menungguKabiro()
    {
        // Get all reviews at 'final' stage that originated from drafts this Kabag reviewed at level 2
        $draftIds = ReviuSurat::where('tingkat', '2')->where('reviewer_id', auth()->id())->where('status', 'disetujui')->pluck('draf_surat_id');
        
        $reviuSurats = ReviuSurat::with(['drafSurat', 'drafSurat.pembuat', 'drafSurat.suratMasuk'])
            ->where('tingkat', 'final')
            ->whereIn('draf_surat_id', $draftIds)
            ->latest()
            ->paginate(10);
            
        $stats = [
            'menunggu' => ReviuSurat::where('tingkat', 'final')->where('status', 'menunggu')->whereIn('draf_surat_id', $draftIds)->count(),
            'disetujui_bulan_ini' => ReviuSurat::where('tingkat', 'final')->where('status', 'disetujui')->whereIn('draf_surat_id', $draftIds)->whereMonth('created_at', now()->month)->count(),
            'dikembalikan' => ReviuSurat::where('tingkat', 'final')->where('status', 'revisi')->whereIn('draf_surat_id', $draftIds)->count(),
        ];
            
        return view('kabag.menunggu-kabiro.index', compact('reviuSurats', 'stats'));
    }

    public function showMenungguKabiro($id)
    {
        $reviuSurat = ReviuSurat::with(['drafSurat', 'drafSurat.suratMasuk', 'drafSurat.pembuat'])->findOrFail($id);
        
        // Find previous reviews for the timeline
        $timelineReviews = ReviuSurat::with('user')
            ->where('draf_surat_id', $reviuSurat->draf_surat_id)
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('kabag.menunggu-kabiro.show', compact('reviuSurat', 'timelineReviews'));
    }

    public function update(Request $request, ReviuSurat $review)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:disetujui,revisi'],
            'catatan' => ['required_if:status,revisi', 'nullable', 'string']
        ]);

        DB::transaction(function() use ($validated, $review) {
            $review->update([
                'status' => $validated['status'],
                'catatan_reviu' => $validated['catatan'] ?? null,
                'reviewer_id' => auth()->id()
            ]);

            if ($validated['status'] === 'disetujui') {
                // Cari Kabiro
                $kabiro = \App\Models\User::where('role', 'kepala_biro')->first();

                // Maju ke review Final (Kabiro)
                ReviuSurat::create([
                    'draf_surat_id' => $review->draf_surat_id,
                    'tingkat' => 'final',
                    'status' => 'menunggu',
                    'reviewer_id' => $kabiro ? $kabiro->id : null
                ]);
            } else {
                // Revisi -> Kembalikan status draf ke revisi
                $review->drafSurat->update(['status' => 'revisi']);
            }
        });

        return redirect()->route('kabag.review.index')->with('success', 'Reviu draf berhasil diselesaikan.');
    }
}
