<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        $query = Disposisi::with(['suratMasuk.drafSurat' => function($q) use ($userId) {
                $q->where('dibuat_oleh', $userId)->latest();
            }, 'pengirim'])
            ->where('ke_user_id', $userId)
            ->where('status', '!=', 'selesai'); // Exclude completed
            
        if ($search = $request->query('search')) {
            $query->whereHas('suratMasuk', function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }
        
        $statusFilter = $request->query('status');
        if ($statusFilter && $statusFilter !== 'Semua Status') {
            if ($statusFilter === 'Menunggu Review') {
                $query->whereHas('suratMasuk.drafSurat', function($q) use ($userId) {
                    $q->where('dibuat_oleh', $userId)->whereNotIn('status', ['selesai', 'draft']);
                });
            } else {
                // map frontend status back to DB status if necessary
            }
        }
            
        $tugas = $query->latest('updated_at')->paginate(10);
        
        $tugasAktifCount = Disposisi::where('ke_user_id', $userId)->where('status', '!=', 'selesai')->count();

        $latestDraftSubquery = \Illuminate\Support\Facades\DB::table('draf_surats')
            ->select(\Illuminate\Support\Facades\DB::raw('MAX(id) as id'))
            ->where('dibuat_oleh', $userId)
            ->groupBy('surat_masuk_id');

        $menungguReviewCount = Disposisi::where('ke_user_id', $userId)
            ->where('status', '!=', 'selesai')
            ->whereHas('suratMasuk.drafSurat', function($q) use ($latestDraftSubquery) {
                $q->whereIn('id', $latestDraftSubquery)
                  ->where('status', 'menunggu_reviu');
            })->count();

        $sedangDikerjakanCount = $tugasAktifCount - $menungguReviewCount;

        $stats = [
            'tugas_aktif' => $tugasAktifCount,
            'sedang_dikerjakan' => $sedangDikerjakanCount,
            'menunggu_review' => $menungguReviewCount,
            'selesai_bulan_ini' => Disposisi::where('ke_user_id', $userId)->where('status', 'selesai')->whereMonth('updated_at', now()->month)->count(),
        ];
            
        return view('staf.tugas.index', compact('tugas', 'stats'));
    }
}
