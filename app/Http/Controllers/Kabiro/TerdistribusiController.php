<?php

namespace App\Http\Controllers\Kabiro;

use App\Http\Controllers\Controller;
use App\Models\SuratFinal;
use Illuminate\Http\Request;

class TerdistribusiController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratFinal::with(['drafSurat', 'drafSurat.suratMasuk'])
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
            if ($via == 'email_fisik') {
                $query->where('via', 'keduanya');
            } else {
                $query->where('via', $via);
            }
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
            
        return view('kabiro.terdistribusi.index', compact('suratFinals'));
    }
}
