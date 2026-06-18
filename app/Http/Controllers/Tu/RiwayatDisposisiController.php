<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use Illuminate\Http\Request;

class RiwayatDisposisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Disposisi::with(['suratMasuk', 'pengirim', 'penerima.unitKerja'])
            ->where('dari_user_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('suratMasuk', function($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'selesai') {
                $query->where('status', 'selesai');
            } elseif ($request->status === 'diproses') {
                $query->where('status', '!=', 'selesai');
            }
        }

        $waktu = $request->input('waktu');
        if ($waktu === 'Bulan Ini') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($waktu === 'Bulan Lalu') {
            $query->whereMonth('created_at', now()->subMonth()->month)
                  ->whereYear('created_at', now()->subMonth()->year);
        } elseif ($waktu === 'Tahun Ini') {
            $query->whereYear('created_at', now()->year);
        }

        $disposisis = $query->latest()->paginate(10)->withQueryString();
            
        $stats = [
            'total_bulan_ini' => Disposisi::where('dari_user_id', auth()->id())->whereMonth('created_at', now()->month)->count(),
            'sedang_diproses' => Disposisi::where('dari_user_id', auth()->id())->whereMonth('created_at', now()->month)->where('status', '!=', 'selesai')->count(),
            'selesai' => Disposisi::where('dari_user_id', auth()->id())->whereMonth('created_at', now()->month)->where('status', 'selesai')->count(),
        ];
        
        return view('tu.disposisi.riwayat', compact('disposisis', 'stats'));
    }
}
