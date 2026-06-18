<?php

namespace App\Http\Controllers\Kasubtim;

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
        }])->latest();

        if ($role === 'kepala_bagian') {
            // Filter only for this Kabag's Sub Tims (for UI we assume all from same parent or mock)
            // Or just show all if we don't have deep hierarchy logic implemented.
            $drafSurats = $drafSuratsQuery->paginate(10);
            
            $stats = [
                'surat_aktif' => 6, // Mock based on UI
                'perlu_review_saya' => 1,
                'selesai_bulan_ini' => 12
            ];

            $header = [
                'title' => 'Progress Surat Bagian',
                'subtitle' => 'Pantau semua surat aktif dalam lingkup ' . ($unitKerja->nama ?? 'Bagian Keuangan'),
                'table_title' => 'Surat Dalam Proses',
                'col3_title' => 'SUB TIM',
            ];
        } elseif ($role === 'tata_usaha') {
            $drafSurats = $drafSuratsQuery->paginate(10);
            
            $stats = [
                'surat_masuk_diproses' => SuratMasuk::where('status', 'diproses')->count(),
                'menunggu_upload_ttd' => SuratFinal::where('status', 'menunggu_ttd')->count(),
                'terdistribusi_bulan_ini' => SuratFinal::where('status', 'terdistribusi')->whereMonth('updated_at', now()->month)->count()
            ];

            $header = [
                'title' => 'Progress Surat — Semua Surat Aktif',
                'subtitle' => 'Pantau status proses semua surat dari awal hingga distribusi',
                'table_title' => 'Daftar Surat Aktif',
                'col3_title' => 'BAGIAN', // Not shown in screenshot, but we keep it
            ];
        } elseif ($role === 'kepala_sub_tim') {
            $drafSurats = $drafSuratsQuery->whereHas('suratMasuk.disposisi', function($q) {
                $q->where('dari_user_id', auth()->id());
            })->where('status', '!=', 'selesai')->paginate(10);

            $riwayatSelesai = DrafSurat::with(['suratMasuk', 'pembuat'])->whereHas('suratMasuk.disposisi', function($q) {
                $q->where('dari_user_id', auth()->id());
            })->where('status', 'selesai')->latest()->take(5)->get();

            $stats = [
                'surat_aktif' => DrafSurat::whereHas('suratMasuk.disposisi', function($q) {
                    $q->where('dari_user_id', auth()->id());
                })->where('status', '!=', 'selesai')->count(),
                
                'perlu_review_saya' => DrafSurat::whereHas('suratMasuk.disposisi', function($q) {
                    $q->where('dari_user_id', auth()->id());
                })->whereHas('reviuSurat', function($q) {
                    $q->where('tingkat', '1')->where('status', 'menunggu');
                })->count(),
                
                'selesai_bulan_ini' => DrafSurat::whereHas('suratMasuk.disposisi', function($q) {
                    $q->where('dari_user_id', auth()->id());
                })->where('status', 'selesai')->whereMonth('updated_at', now()->month)->count()
            ];

            $header = [
                'title' => 'Progress Surat Tim',
                'subtitle' => 'Pantau semua surat aktif dalam lingkup Sub Tim',
                'table_title' => 'Surat Dalam Proses',
                'col3_title' => 'DIKERJAKAN OLEH (STAF)',
            ];
            
            return view('kasubtim.progress.index', compact('drafSurats', 'stats', 'header', 'role', 'riwayatSelesai'));
        } elseif ($role === 'staf') {
            $drafSurats = DrafSurat::with(['suratMasuk', 'pembuat.unitKerja', 'reviuSurat' => function($q) {
                $q->latest();
            }])->where('dibuat_oleh', auth()->id())->where('status', '!=', 'selesai')->latest()->paginate(10);

            $riwayatSelesai = DrafSurat::with(['suratMasuk'])->where('dibuat_oleh', auth()->id())->where('status', 'selesai')->latest()->take(5)->get();
            
            $stats = [
                'tugas_aktif' => \App\Models\Disposisi::where('ke_user_id', auth()->id())->whereIn('status', ['menunggu', 'diproses'])->count(),
                'menunggu_review' => DrafSurat::where('dibuat_oleh', auth()->id())->where('status', 'menunggu_reviu')->count(),
                'selesai_bulan_ini' => \App\Models\Disposisi::where('ke_user_id', auth()->id())->where('status', 'selesai')->whereMonth('updated_at', now()->month)->count()
            ];

            $header = [
                'title' => 'Progress Surat Saya',
                'subtitle' => 'Pantau status tindak lanjut surat yang ditugaskan kepada Anda',
                'table_title' => 'Daftar Surat Aktif',
                'col3_title' => '',
            ];
            
            return view('kasubtim.progress.index', compact('drafSurats', 'stats', 'header', 'role', 'riwayatSelesai'));
        } else {
            $drafSurats = $drafSuratsQuery->paginate(10);
            
            $stats = [
                'perlu_ttd' => ReviuSurat::where('tingkat', 'final')->where('status', 'menunggu')->count(),
                'disetujui_bulan_ini' => ReviuSurat::where('tingkat', 'final')->where('status', 'disetujui')->whereMonth('updated_at', now()->month)->count(),
                'total_surat_keluar' => SuratFinal::count()
            ];

            $header = [
                'title' => 'Progress Surat — Biro Keuangan & BMN',
                'subtitle' => 'Pantau semua surat aktif di seluruh Biro',
                'table_title' => 'Semua Surat Aktif',
                'col3_title' => 'BAGIAN',
            ];
        }
        
        
        return view('kasubtim.progress.index', compact('drafSurats', 'stats', 'header', 'role'));
    }

    public function show($id)
    {
        $drafSurat = DrafSurat::with(['suratMasuk', 'pembuat.unitKerja', 'reviuSurat.user.unitKerja', 'suratFinal', 'reviuSurat' => function($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);
        
        $hasAccess = \App\Models\Disposisi::where('surat_masuk_id', $drafSurat->surat_masuk_id)
            ->where('dari_user_id', auth()->id())
            ->exists();
        abort_unless($hasAccess, 403, 'Anda tidak memiliki akses ke progress draft ini.');
        
        return view('kasubtim.progress.show', compact('drafSurat'));
    }
}



