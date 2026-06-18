<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Models\SuratFinal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    private function getArsipData($request = null)
    {
        $smQuery = SuratMasuk::latest();
        $sfQuery = SuratFinal::with('drafSurat')->latest();
        
        if ($request) {
            if ($search = $request->query('search')) {
                $smQuery->where(function($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
                });
                $sfQuery->whereHas('drafSurat', function($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
                });
            }
            if ($tahun = $request->query('tahun')) {
                $smQuery->whereYear('created_at', $tahun);
                $sfQuery->whereYear('created_at', $tahun);
            }
        }

        $suratMasuks = $smQuery->get()->map(function($surat) {
            $surat->jenis = 'Surat Masuk';
            $surat->kategori = 'Surat Dinas'; // Dummy for UI
            $surat->lokasi_fisik = 'Lemari A - Rak 2'; // Dummy for UI
            return $surat;
        });
        
        $suratKeluars = $sfQuery->get()->map(function($surat) {
            $surat->jenis = 'Surat Keluar';
            $surat->kategori = 'Surat Undangan'; // Dummy for UI
            $surat->lokasi_fisik = 'Lemari A - Rak 3'; // Dummy for UI
            // map attributes so it matches table
            $surat->pengirim = $surat->pembuat->name ?? 'Internal';
            $surat->tanggal_terima = $surat->created_at;
            $surat->nomor_agenda = $surat->drafSurat->nomor_agenda ?? '-';
            return $surat;
        });

        $all = $suratMasuks->concat($suratKeluars);
        
        if ($request && $request->query('jenis')) {
            $all = $all->where('jenis', $request->query('jenis'));
        }
        if ($request && $request->query('kategori')) {
            $all = $all->where('kategori', $request->query('kategori'));
        }

        return $all->sortByDesc('created_at')->values();
    }

    public function index(Request $request)
    {
        $arsips = $this->getArsipData($request);
        return view('admin.arsip.index', compact('arsips'));
    }

    public function exportPdf(Request $request)
    {
        $arsips = $this->getArsipData($request);
        
        $pdf = Pdf::loadView('admin.arsip.pdf', compact('arsips'))->setPaper('a4', 'landscape');
        
        return $pdf->download('Laporan_Arsip_Surat.pdf');
    }

    public function pdf(Request $request, $id)
    {
        $jenis = $request->query('jenis');
        if ($jenis == 'masuk') {
            $surat = SuratMasuk::findOrFail($id);
            $view = 'admin.arsip.pdf-masuk';
            $filename = 'Surat_Masuk_' . str_replace('/', '_', $surat->nomor_surat ?? $surat->id) . '.pdf';
        } else {
            $surat = SuratFinal::with('drafSurat')->findOrFail($id);
            $view = 'admin.arsip.pdf-keluar';
            $filename = 'Surat_Keluar_' . str_replace('/', '_', $surat->drafSurat->nomor_surat ?? $surat->id) . '.pdf';
        }
        
        // Buat view sederhana jika belum ada template khusus
        $pdf = Pdf::loadHTML("
            <style>body { font-family: sans-serif; }</style>
            <h2>Detail " . ($jenis == 'masuk' ? 'Surat Masuk' : 'Surat Keluar') . "</h2>
            <hr>
            <p><strong>Nomor:</strong> " . ($jenis == 'masuk' ? $surat->nomor_surat : ($surat->drafSurat->nomor_surat ?? '-')) . "</p>
            <p><strong>Perihal:</strong> " . ($jenis == 'masuk' ? $surat->perihal : ($surat->drafSurat->perihal ?? '-')) . "</p>
            <p><strong>Pengirim:</strong> " . ($jenis == 'masuk' ? $surat->pengirim : ($surat->pembuat->name ?? '-')) . "</p>
            <p><strong>Tanggal:</strong> " . ($jenis == 'masuk' ? $surat->tanggal_surat : ($surat->created_at->format('Y-m-d'))) . "</p>
            " . ($jenis == 'masuk' ? '' : "<p><strong>Penandatangan:</strong> " . ($surat->penandatangan->name ?? '-') . "</p>") . "
        ");
        
        return $pdf->download($filename);
    }
}
