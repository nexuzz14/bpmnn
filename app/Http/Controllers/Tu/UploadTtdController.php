<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\DrafSurat;
use App\Models\SuratFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadTtdController extends Controller
{
    public function index()
    {
        $drafSurats = DrafSurat::with(['suratMasuk', 'reviuTerkini'])
            ->where('status', 'menunggu_ttd')
            ->latest()
            ->paginate(10);
            
        return view('tu.upload-ttd.index', compact('drafSurats'));
    }

    public function create(DrafSurat $drafSurat)
    {
        return view('tu.upload-ttd.create', compact('drafSurat'));
    }

    public function store(Request $request, DrafSurat $drafSurat)
    {
        $validated = $request->validate([
            'file_final' => ['required', 'file', 'mimes:pdf', 'max:10240'], // Max 10MB for scan
            'nomor_surat_final' => ['required', 'string', 'max:255']
        ]);

        DB::transaction(function() use ($request, $validated, $drafSurat) {
            $path = $request->file('file_final')->store('surat-final', 'public');

            SuratFinal::create([
                'draf_surat_id' => $drafSurat->id,
                'nomor_surat_final' => $validated['nomor_surat_final'],
                'file_ttd' => $path,
                'file_lampiran' => null,
                'status' => 'belum',
                'ditandatangani_oleh' => $drafSurat->reviuSurat()->where('tingkat', 'final')->first()->reviewer_id ?? auth()->id(),
                'tanggal_ttd' => now()
            ]);

            $drafSurat->update(['status' => 'selesai']);
        });

        return redirect()->route('tu.surat-final.index')->with('success', 'Surat Final bertandatangan berhasil diunggah dan siap didistribusikan.');
    }
}
