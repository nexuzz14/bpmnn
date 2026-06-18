<?php

namespace App\Http\Controllers\Kabiro;

use App\Http\Controllers\Controller;
use App\Models\ReviuSurat;
use App\Models\SuratFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewFinalController extends Controller
{
    public function index()
    {
        $reviuSurats = ReviuSurat::with(['drafSurat.suratMasuk', 'drafSurat.pembuat'])
            ->where('tingkat', 'final')
            ->latest()
            ->paginate(10);
            
        return view('kabiro.review-final.index', compact('reviuSurats'));
    }

    public function show(ReviuSurat $reviewFinal)
    {
        return view('kabiro.review-final.show', compact('reviewFinal'));
    }

    public function update(Request $request, ReviuSurat $reviewFinal)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:disetujui,revisi'],
            'catatan' => ['required_if:status,revisi', 'nullable', 'string']
        ]);

        DB::transaction(function() use ($validated, $reviewFinal) {
            $reviewFinal->update([
                'status' => $validated['status'],
                'catatan_reviu' => $validated['catatan'] ?? null,
                'reviewer_id' => auth()->id()
            ]);

            if ($validated['status'] === 'disetujui') {
                $reviewFinal->drafSurat->update(['status' => 'menunggu_ttd']);
            } else {
                $reviewFinal->drafSurat->update(['status' => 'revisi']);
            }
        });

        $message = $validated['status'] === 'disetujui' 
            ? 'Draf surat berhasil disetujui. Surat dikirim ke Tata Usaha untuk di-upload versi bertandatangan fisik.'
            : 'Surat dikembalikan ke Staf untuk direvisi.';

        return redirect()->route('kabiro.review-final.index')->with('success', $message);
    }
}
