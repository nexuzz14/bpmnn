<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\SuratFinal;
use Illuminate\Http\Request;

class TerdistribusiController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratFinal::whereHas('drafSurat.pembuat.unitKerja', function($q) {
                // Simplified for now: assuming Kabag sees everything or we filter later
                // In real app, filter by auth()->user()->unit_kerja_id
            })
            ->with(['drafSurat', 'drafSurat.suratMasuk'])
            ->where('status', 'terdistribusi');

        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat_final', 'like', "%{$search}%")
                  ->orWhereHas('drafSurat.suratMasuk', function($q2) use ($search) {
                      $q2->where('perihal', 'like', "%{$search}%");
                  });
            });
        }

        if ($via = $request->query('via')) {
            $query->where('via', $via);
        }

        if ($periode = $request->query('periode')) {
            if ($periode == 'bulan_ini') {
                $query->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year);
            } elseif ($periode == 'bulan_lalu') {
                $query->whereMonth('updated_at', now()->subMonth()->month)->whereYear('updated_at', now()->subMonth()->year);
            } elseif ($periode == 'tahun_ini') {
                $query->whereYear('updated_at', now()->year);
            }
        }

        $suratFinals = $query->latest()->paginate(10)->withQueryString();
            
        $stats = [
            'total' => SuratFinal::where('status', 'terdistribusi')->whereMonth('created_at', now()->month)->count(),
            'via_email' => SuratFinal::where('status', 'terdistribusi')->where('via', 'email')->count(),
            'via_fisik' => SuratFinal::where('status', 'terdistribusi')->where('via', 'fisik')->count(),
            'keduanya' => SuratFinal::where('status', 'terdistribusi')->where('via', 'keduanya')->count(),
        ];
            
        return view('kabag.terdistribusi.index', compact('suratFinals', 'stats'));
    }

    public function show($id)
    {
        $suratFinal = SuratFinal::with(['drafSurat.suratMasuk', 'drafSurat.pembuat.unitKerja', 'drafSurat.reviuSurat' => function($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);
        
        return view('kabag.terdistribusi.show', compact('suratFinal'));
    }
}
