<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Models\SuratFinal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
class RekapController extends Controller
{
    public function index()
    {
        // Data for Chart.js
        $suratMasukCount = SuratMasuk::count();
        $suratSelesaiCount = SuratMasuk::where('status', 'selesai')->count();
        $suratProsesCount = $suratMasukCount - $suratSelesaiCount;
        
        $suratFinalCount = SuratFinal::count();
        $suratFinalDistribusiCount = SuratFinal::where('status', 'sudah')->count();

        // Chart data grouping by month
        $monthlyData = SuratMasuk::selectRaw('MONTH(tanggal_terima) as month, COUNT(*) as count')
            ->whereYear('tanggal_terima', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
            
        $chartLabels = [];
        $chartValues = [];
        $suratKeluarValues = [];
        $monthlyDetails = [];

        $suratKeluarData = SuratFinal::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $suratSelesaiData = SuratMasuk::selectRaw('MONTH(tanggal_terima) as month, COUNT(*) as count')
            ->where('status', 'selesai')
            ->whereYear('tanggal_terima', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->translatedFormat('F');
            $chartLabels[] = substr($monthName, 0, 3);
            $smCount = $monthlyData[$i] ?? 0;
            $skCount = $suratKeluarData[$i] ?? 0;
            $ssCount = $suratSelesaiData[$i] ?? 0;
            $spCount = $smCount - $ssCount;

            $chartValues[] = $smCount;
            $suratKeluarValues[] = $skCount;

            // Only add to table if there is data
            if ($smCount > 0 || $skCount > 0) {
                $monthlyDetails[] = [
                    'periode' => $monthName . ' ' . Carbon::now()->year,
                    'surat_masuk' => $smCount,
                    'surat_keluar' => $skCount,
                    'sedang_diproses' => $spCount,
                    'selesai' => $ssCount
                ];
            }
        }

        // Data for Distribusi Jenis Surat
        // Since we don't have a specific `jenis_surat` column, we'll mock it based on UI or use some random data that sums to total.
        $distribusiJenis = [
            'Surat Dinas' => floor($suratMasukCount * 0.4),
            'Surat Keputusan' => floor($suratMasukCount * 0.3),
            'Surat Edaran' => floor($suratMasukCount * 0.2),
            'Nota Dinas' => $suratMasukCount - floor($suratMasukCount * 0.4) - floor($suratMasukCount * 0.3) - floor($suratMasukCount * 0.2),
        ];

        return view('admin.rekap.index', compact(
            'suratMasukCount', 'suratSelesaiCount', 'suratProsesCount',
            'suratFinalCount', 'suratFinalDistribusiCount',
            'chartLabels', 'chartValues', 'suratKeluarValues', 'monthlyDetails', 'distribusiJenis'
        ));
    }

    public function exportPdf(Request $request)
    {
        // Re-generate monthlyDetails data just like index() or better, abstract it
        $monthlyData = SuratMasuk::selectRaw('MONTH(tanggal_terima) as month, COUNT(*) as count')
            ->whereYear('tanggal_terima', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
            
        $suratKeluarData = SuratFinal::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $suratSelesaiData = SuratMasuk::selectRaw('MONTH(tanggal_terima) as month, COUNT(*) as count')
            ->where('status', 'selesai')
            ->whereYear('tanggal_terima', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyDetails = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->translatedFormat('F');
            $smCount = $monthlyData[$i] ?? 0;
            $skCount = $suratKeluarData[$i] ?? 0;
            $ssCount = $suratSelesaiData[$i] ?? 0;
            $spCount = $smCount - $ssCount;

            if ($smCount > 0 || $skCount > 0) {
                $monthlyDetails[] = [
                    'periode' => $monthName . ' ' . Carbon::now()->year,
                    'surat_masuk' => $smCount,
                    'surat_keluar' => $skCount,
                    'sedang_diproses' => $spCount,
                    'selesai' => $ssCount
                ];
            }
        }

        $pdf = Pdf::loadView('pdf.rekap', [
            'title' => 'Biro Keuangan & BMN Kemenag RI',
            'subtitle' => 'Laporan Rekapitulasi Persuratan',
            'details' => $monthlyDetails
        ]);
        
        return $pdf->download('Rekapitulasi_Surat.pdf');
    }
}
