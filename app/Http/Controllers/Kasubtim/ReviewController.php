<?php

namespace App\Http\Controllers\Kasubtim;

use App\Http\Controllers\Controller;
use App\Models\ReviuSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Reviu tingkat 1 yang menunggu, pastikan dari disposisi Kasubtim ini
        $reviuSurats = ReviuSurat::with(['drafSurat.suratMasuk', 'drafSurat.pembuat'])
            ->where('tingkat', '1')
            ->where('status', 'menunggu')
            ->whereHas('drafSurat.suratMasuk.disposisi', function($q) use ($userId) {
                $q->where('dari_user_id', $userId);
            })
            ->latest()
            ->paginate(10);

        $stats = [
            'menunggu' => ReviuSurat::where('tingkat', '1')->where('status', 'menunggu')->count(),
            'direview_bulan_ini' => ReviuSurat::where('tingkat', '1')->where('status', '!=', 'menunggu')->whereMonth('updated_at', now()->month)->count(),
            'revisi' => ReviuSurat::where('tingkat', '1')->where('status', 'revisi')->count(),
        ];
            
        return view('kasubtim.review.index', compact('reviuSurats', 'stats'));
    }

    public function show(ReviuSurat $review)
    {
        $hasAccess = \App\Models\Disposisi::where('surat_masuk_id', $review->drafSurat->surat_masuk_id)
            ->where('dari_user_id', auth()->id())
            ->exists();
        abort_unless($hasAccess, 403, 'Anda tidak diizinkan mereview draft ini.');

        return view('kasubtim.review.show', compact('review'));
    }

    public function update(Request $request, ReviuSurat $review)
    {
        $hasAccess = \App\Models\Disposisi::where('surat_masuk_id', $review->drafSurat->surat_masuk_id)
            ->where('dari_user_id', auth()->id())
            ->exists();
        abort_unless($hasAccess, 403, 'Anda tidak diizinkan memperbarui review ini.');

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
                // Maju ke review Tingkat 2 (Kabag)
                $kabag = \App\Models\User::where('role', 'kepala_bagian')->first();

                ReviuSurat::create([
                    'draf_surat_id' => $review->draf_surat_id,
                    'tingkat' => '2',
                    'status' => 'menunggu',
                    'reviewer_id' => $kabag ? $kabag->id : null
                ]);
            } else {
                // Revisi
                $review->drafSurat->update(['status' => 'revisi']);
            }
        });

        return redirect()->route('kasubtim.review.index')->with('success', 'Reviu draf berhasil diselesaikan.');
    }
}
