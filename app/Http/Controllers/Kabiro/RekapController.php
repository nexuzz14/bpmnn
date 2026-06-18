<?php

namespace App\Http\Controllers\Kabiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratFinal;
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
        $chartDireview = [];
        $chartDisetujui = [];
        $chartDikembalikan = [];

        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::create()->month($i)->translatedFormat('F');
            $periode = $month . ' ' . $year;
            $chartLabels[] = substr($month, 0, 3);

            $suratMasuk = \App\Models\ReviuSurat::where('reviewer_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            $disetujui = \App\Models\ReviuSurat::where('reviewer_id', $userId)
                ->where('status', 'disetujui')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            $dikembalikan = \App\Models\ReviuSurat::where('reviewer_id', $userId)
                ->where('status', 'revisi')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            $revius = \App\Models\ReviuSurat::where('reviewer_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->whereNotNull('updated_at')
                ->whereColumn('updated_at', '!=', 'created_at')
                ->get();
                
            $totalDays = 0;
            $count = 0;
            foreach ($revius as $r) {
                $totalDays += Carbon::parse($r->created_at)->diffInDays(Carbon::parse($r->updated_at));
                $count++;
            }
            $avgTime = $count > 0 ? ($totalDays / $count) : 0;

            $waktuReview = $avgTime ? round($avgTime, 1) . ' hari' : '-';

            if ($suratMasuk > 0) {
                $rekapData[] = [
                    'periode' => $periode,
                    'surat_masuk' => $suratMasuk,
                    'disetujui' => $disetujui,
                    'dikembalikan' => $dikembalikan,
                    'waktu_review' => $waktuReview
                ];
            }

            $chartDireview[] = $suratMasuk;
            $chartDisetujui[] = $disetujui;
            $chartDikembalikan[] = $dikembalikan;
        }

        return view('kabiro.rekap.index', compact(
            'rekapData', 
            'chartLabels', 
            'chartDireview', 
            'chartDisetujui', 
            'chartDikembalikan',
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

            $suratMasuk = \App\Models\ReviuSurat::where('reviewer_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            $disetujui = \App\Models\ReviuSurat::where('reviewer_id', $userId)
                ->where('status', 'disetujui')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            $dikembalikan = \App\Models\ReviuSurat::where('reviewer_id', $userId)
                ->where('status', 'revisi')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();

            $revius = \App\Models\ReviuSurat::where('reviewer_id', $userId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->whereNotNull('updated_at')
                ->whereColumn('updated_at', '!=', 'created_at')
                ->get();
                
            $totalDays = 0;
            $count = 0;
            foreach ($revius as $r) {
                $totalDays += Carbon::parse($r->created_at)->diffInDays(Carbon::parse($r->updated_at));
                $count++;
            }
            $avgTime = $count > 0 ? ($totalDays / $count) : 0;

            $waktuReview = $avgTime ? round($avgTime, 1) . ' hari' : '-';

            if ($suratMasuk > 0) {
                $rekapData[] = [
                    'periode' => $periode,
                    'surat_masuk' => $suratMasuk,
                    'disetujui' => $disetujui,
                    'dikembalikan' => $dikembalikan,
                    'waktu_review' => $waktuReview
                ];
            }
        }

        $pdf = Pdf::loadView('pdf.rekap', [
            'title' => 'Biro Keuangan & BMN Kemenag RI',
            'subtitle' => 'Laporan Rekapitulasi Kabiro',
            'details' => $rekapData
        ]);
        
        return $pdf->download('Rekapitulasi_Kabiro_'.$year.'.pdf');
    }
}
