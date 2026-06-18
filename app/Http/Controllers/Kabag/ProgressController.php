<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;

use App\Models\DrafSurat;
use App\Models\ReviuSurat;
use App\Models\SuratFinal;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        $unitKerja = auth()->user()->unitKerja;
        
        $drafSuratsQuery = DrafSurat::with(['suratMasuk', 'pembuat.unitKerja', 'reviuSurat' => function($q) {
            $q->latest();
        }])->where('status', '!=', 'selesai')->latest();

        $drafSurats = $drafSuratsQuery->paginate(10);
        
        $stats = [
            'surat_aktif' => DrafSurat::where('status', '!=', 'selesai')->count(),
            'perlu_review_saya' => ReviuSurat::where('tingkat', '2')->where('status', 'menunggu')->count(),
            'selesai_bulan_ini' => DrafSurat::where('status', 'selesai')->whereMonth('updated_at', now()->month)->count()
        ];

        $riwayatSelesai = DrafSurat::with(['suratMasuk', 'pembuat.unitKerja'])
            ->where('status', 'selesai')
            ->latest()
            ->take(5)
            ->get();

        $header = [
            'title' => 'Progress Surat Bagian',
            'subtitle' => 'Pantau semua surat aktif dalam lingkup ' . ($unitKerja->nama ?? 'Bagian Keuangan'),
            'table_title' => 'Surat Dalam Proses',
            'col3_title' => 'SUB TIM',
        ];
        
        return view('kabag.progress.index', compact('drafSurats', 'stats', 'header', 'role', 'riwayatSelesai'));
    }

    public function show($id)
    {
        $drafSurat = DrafSurat::with(['suratMasuk', 'pembuat.unitKerja', 'reviuSurat.user.unitKerja', 'suratFinal', 'reviuSurat' => function($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);
        
        return view('kabag.progress.show', compact('drafSurat'));
    }
}



