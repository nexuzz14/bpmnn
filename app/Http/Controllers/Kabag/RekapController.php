<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disposisi;
use App\Models\ReviuSurat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapController extends Controller
{
    public function index()
    {
        $year = request()->query('tahun', now()->year);
        $userId = auth()->id();

        $rekapData = [];
        $chartLabels = [];
        $chartDisposisiMasuk = [];
        $chartDraftDireview = [];
        $chartSelesai = [];

        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::create()->month($i)->translatedFormat('F');
            $periode = $month . ' ' . $year;
            $chartLabels[] = substr($month, 0, 3);

            // Disposisi Masuk
            $disposisiMasuk = Disposisi::where('ke_user_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            // Draft Direview
            $draftDireview = ReviuSurat::where('reviewer_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            // Disetujui
            $disetujui = ReviuSurat::where('reviewer_id', $userId)
                ->where('status', 'disetujui')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            // Dikembalikan
            $dikembalikan = ReviuSurat::where('reviewer_id', $userId)
                ->where('status', 'revisi')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            // Rata-rata waktu review
            $completedReviews = ReviuSurat::where('reviewer_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->whereIn('status', ['disetujui', 'revisi'])
                ->get();
            
            $totalHours = 0;
            $count = 0;
            foreach ($completedReviews as $r) {
                if ($r->created_at && $r->updated_at && $r->updated_at->ne($r->created_at)) {
                    $totalHours += $r->created_at->diffInHours($r->updated_at);
                    $count++;
                }
            }
            $avgTime = $count > 0 ? ($totalHours / 24) / $count : 0;

            $waktuReview = $avgTime > 0 ? round($avgTime, 1) . ' hari' : '-';

            if ($disposisiMasuk > 0 || $draftDireview > 0) {
                $rekapData[] = [
                    'periode' => $periode,
                    'disposisi_masuk' => $disposisiMasuk,
                    'draft_direview' => $draftDireview,
                    'disetujui' => $disetujui,
                    'dikembalikan' => $dikembalikan,
                    'waktu_review' => $waktuReview
                ];
            }

            $chartDisposisiMasuk[] = $disposisiMasuk;
            $chartDraftDireview[] = $draftDireview;
            $chartSelesai[] = $disetujui;
        }

        return view('kabag.rekap.index', compact(
            'rekapData', 
            'chartLabels', 
            'chartDisposisiMasuk', 
            'chartDraftDireview', 
            'chartSelesai',
            'year'
        ));
    }

    public function exportPdf(Request $request)
    {
        $year = $request->query('tahun', now()->year);
        $userId = auth()->id();
        $rekapData = [];

        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::create()->month($i)->translatedFormat('F');
            $periode = $month . ' ' . $year;

            // Disposisi Masuk
            $disposisiMasuk = Disposisi::where('ke_user_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            // Draft Direview
            $draftDireview = ReviuSurat::where('reviewer_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            // Disetujui
            $disetujui = ReviuSurat::where('reviewer_id', $userId)
                ->where('status', 'disetujui')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            // Dikembalikan
            $dikembalikan = ReviuSurat::where('reviewer_id', $userId)
                ->where('status', 'revisi')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            // Rata-rata waktu review
            $avgTime = ReviuSurat::where('reviewer_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->whereNotNull('updated_at')
                ->whereColumn('updated_at', '!=', 'created_at')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(DAY, created_at, updated_at)) as avg_days'))
                ->value('avg_days');

            $waktuReview = $avgTime ? round($avgTime, 1) . ' hari' : '-';

            if ($disposisiMasuk > 0 || $draftDireview > 0) {
                $rekapData[] = [
                    'periode' => $periode,
                    'disposisi_masuk' => $disposisiMasuk,
                    'draft_direview' => $draftDireview,
                    'disetujui' => $disetujui,
                    'dikembalikan' => $dikembalikan,
                    'waktu_review' => $waktuReview
                ];
            }
        }

        $pdf = Pdf::loadView('pdf.rekap', [
            'title' => 'Biro Keuangan & BMN Kemenag RI',
            'subtitle' => 'Laporan Rekapitulasi Kabag',
            'details' => $rekapData
        ]);
        
        return $pdf->download('Rekapitulasi_Kabag_'.$year.'.pdf');
    }
}

