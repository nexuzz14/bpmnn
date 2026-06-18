<?php

namespace App\Http\Controllers\Kasubtim;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\ReviuSurat;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // 1. Ambil history Reviu
        $revius = ReviuSurat::with(['drafSurat.suratMasuk'])
            ->where('reviewer_id', $userId)
            ->whereIn('status', ['disetujui', 'revisi'])
            ->latest()
            ->get();

        // 2. Ambil history Penugasan Disposisi
        $disposisis = Disposisi::with(['suratMasuk', 'penerima'])
            ->where('dari_user_id', $userId)
            ->latest()
            ->get();

        // Gabungkan dan urutkan
        $activities = collect();

        foreach ($revius as $r) {
            $activities->push((object)[
                'type' => 'reviu',
                'action' => $r->status === 'disetujui' ? 'Menyetujui Draft' : 'Mengembalikan Draft',
                'status_label' => ucfirst($r->status),
                'status_color' => $r->status === 'disetujui' ? 'bg-[#dcfce7] text-[#166534]' : 'bg-[#fee2e2] text-[#991b1b]',
                'description' => 'Draft "' . $r->drafSurat->suratMasuk->perihal . '" ' . ($r->status === 'disetujui' ? 'disetujui dan diteruskan ke Kabag' : 'dikembalikan ke ' . ($r->drafSurat->pembuat->name ?? 'Staf') . ' untuk revisi'),
                'ref_number' => 'DRAFT-' . str_pad($r->draf_surat_id, 3, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($r->created_at)),
                'date' => $r->updated_at,
            ]);
        }

        foreach ($disposisis as $d) {
            $activities->push((object)[
                'type' => 'disposisi',
                'action' => 'Menugaskan Staf',
                'status_label' => 'Ditugaskan',
                'status_color' => 'bg-[#e0e7ff] text-[#3730a3]',
                'description' => 'Disposisi #' . explode('/', $d->suratMasuk->nomor_surat)[0] . ' ditugaskan kepada ' . ($d->penerima->name ?? 'Staf') . ' untuk penyusunan draft',
                'ref_number' => 'Disposisi #' . explode('/', $d->suratMasuk->nomor_surat)[0],
                'date' => $d->created_at,
            ]);
        }

        $activities = $activities->sortByDesc('date');

        // Untuk Search / Filter di collection jika ada request
        if ($search = $request->query('search')) {
            $search = strtolower($search);
            $activities = $activities->filter(function($act) use ($search) {
                return str_contains(strtolower($act->description), $search) || str_contains(strtolower($act->ref_number), $search);
            });
        }
        
        if ($type = $request->query('type')) {
            $activities = $activities->where('type', $type);
        }

        if ($time = $request->query('time')) {
            if ($time == 'minggu_ini') {
                $activities = $activities->filter(function($act) {
                    return \Carbon\Carbon::parse($act->date)->greaterThanOrEqualTo(now()->startOfWeek());
                });
            } elseif ($time == 'bulan_ini') {
                $activities = $activities->filter(function($act) {
                    return \Carbon\Carbon::parse($act->date)->greaterThanOrEqualTo(now()->startOfMonth());
                });
            }
        }

        // Dummy manual pagination implementation on collection if needed, but for simplicity let's just pass the whole collection for now or use LengthAwarePaginator.
        // Let's use simple manual pagination
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $activitiesPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $activities->forPage($currentPage, $perPage),
            $activities->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $stats = [
            'total' => $activities->where('date', '>=', now()->startOfMonth())->count(),
            'disetujui' => $revius->where('status', 'disetujui')->count(),
            'dikembalikan' => $revius->where('status', 'revisi')->count(),
            'ditugaskan' => $disposisis->count()
        ];
        
        return view('kasubtim.riwayat.index', [
            'activities' => $activitiesPaginated,
            'stats' => $stats
        ]);
    }
}
