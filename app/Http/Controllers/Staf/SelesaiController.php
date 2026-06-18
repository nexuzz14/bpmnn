<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\DrafSurat;
use Illuminate\Http\Request;

class SelesaiController extends Controller
{
    public function index(Request $request)
    {
        $query = DrafSurat::with(['suratMasuk', 'suratFinal'])
            ->where('dibuat_oleh', auth()->id())
            ->whereHas('reviuSurat', function($q) {
                // Assuming it's 'selesai' if Kasubtim has approved it and it moved forward
                // Or we can just check if status_akhir is somehow final, but for Staf, 
                // any draft that has been approved by Kasubtim might be considered "Selesai" from their perspective.
                $q->where('tingkat', '1')->where('status', 'disetujui');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('suratMasuk', function($qSm) use ($search) {
                      $qSm->where('perihal', 'like', "%{$search}%");
                  });
            });
        }

        $waktu = $request->input('waktu', 'Bulan Ini');
        if ($waktu === 'Bulan Ini') {
            $query->whereMonth('updated_at', now()->month)
                  ->whereYear('updated_at', now()->year);
        } elseif ($waktu === 'Bulan Lalu') {
            $query->whereMonth('updated_at', now()->subMonth()->month)
                  ->whereYear('updated_at', now()->subMonth()->year);
        }

        $drafSurats = $query->latest()->paginate(10)->withQueryString();
            
        $stats = [
            'selesai_bulan_ini' => DrafSurat::where('dibuat_oleh', auth()->id())
                ->whereHas('reviuSurat', function($q) {
                    $q->where('tingkat', '1')->where('status', 'disetujui');
                })->whereMonth('updated_at', now()->month)->count(),
            'total_selesai' => DrafSurat::where('dibuat_oleh', auth()->id())
                ->whereHas('reviuSurat', function($q) {
                    $q->where('tingkat', '1')->where('status', 'disetujui');
                })->count(),
            'rata_waktu' => '2.3 hari', // Dummy for now
        ];
        
        return view('staf.selesai.index', compact('drafSurats', 'stats'));
    }

    public function show($id)
    {
        $drafSurat = DrafSurat::with(['suratMasuk', 'pembuat.unitKerja', 'reviuSurat' => function($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);
        
        abort_if($drafSurat->dibuat_oleh != auth()->id(), 403, 'Anda tidak diizinkan mengakses surat ini.');
        
        return view('staf.selesai.show', compact('drafSurat'));
    }
}
