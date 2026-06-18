<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\SuratFinal;
use Illuminate\Http\Request;

class SuratFinalController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratFinal::with(['drafSurat.suratMasuk']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat_final', 'like', "%{$search}%")
                  ->orWhereHas('drafSurat.suratMasuk', function($qSm) use ($search) {
                      $qSm->where('perihal', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tahun')) {
            $query->whereYear('updated_at', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('updated_at', $request->bulan);
        }

        $suratFinals = $query->latest()->paginate(10)->withQueryString();
            
        return view('tu.surat-final.index', compact('suratFinals'));
    }

    public function show($id)
    {
        $suratFinal = SuratFinal::with(['drafSurat.suratMasuk', 'drafSurat.pembuat'])->findOrFail($id);
        
        return view('tu.surat-final.show', compact('suratFinal'));
    }

    public function distribute(SuratFinal $suratFinal)
    {
        $suratFinal->update([
            'status' => 'sudah'
        ]);

        return redirect()->route('tu.surat-final.index')->with('success', 'Surat Final berhasil didistribusikan.');
    }
}
