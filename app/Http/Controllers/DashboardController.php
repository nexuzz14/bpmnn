<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SuratMasuk;
use App\Models\Disposisi;
use App\Models\SuratFinal;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'tata_usaha' => redirect()->route('tu.dashboard'),
            'kepala_bagian' => redirect()->route('kabag.dashboard'),
            'kepala_sub_tim' => redirect()->route('kasubtim.dashboard'),
            'staf' => redirect()->route('staf.dashboard'),
            'kepala_biro' => redirect()->route('kabiro.dashboard'),
            default => redirect('/'),
        };
    }

    public function admin()
    {
        return view('admin.dashboard');
    }

    public function tataUsaha()
    {
        $suratMasukCount = SuratMasuk::count();
        $suratDalamProsesCount = SuratMasuk::whereIn('status', ['diproses', 'menunggu_reviu', 'revisi'])->count();
        $suratBelumDisposisiCount = SuratMasuk::where('status', 'diterima')->count();
        $suratSelesaiCount = SuratMasuk::where('status', 'selesai')->count();
        
        $suratMasukPerluDiproses = SuratMasuk::where('status', 'diterima')->take(5)->get();
        $siapUploadTtd = \App\Models\DrafSurat::with(['suratMasuk'])->where('status', 'menunggu_ttd')->take(5)->get();

        return view('tu.dashboard', compact(
            'suratMasukCount', 'suratDalamProsesCount', 'suratBelumDisposisiCount', 'suratSelesaiCount',
            'suratMasukPerluDiproses', 'siapUploadTtd'
        ));
    }

    public function kepalaBagian()
    {
        return view('kabag.dashboard');
    }

    public function kepalaSubTim()
    {
        $userId = auth()->id();
        
        $disposisiBaru = \App\Models\Disposisi::where('ke_user_id', $userId)
            ->where('status', 'menunggu')
            ->count();
            
        $draftPerluReview = \App\Models\ReviuSurat::where('reviewer_id', $userId)
            ->where('tingkat', '1')
            ->where('status', 'menunggu')
            ->count();
            
        $suratSelesai = \App\Models\ReviuSurat::where('reviewer_id', $userId)
            ->whereMonth('updated_at', now()->month)
            ->where('status', 'disetujui')
            ->count();
            
        return view('kasubtim.dashboard', compact('disposisiBaru', 'draftPerluReview', 'suratSelesai'));
    }

    public function staf()
    {
        $userId = auth()->id();

        // Retrieve all active disposisi to calculate accurate stats
        $allActiveDisposisi = Disposisi::with(['suratMasuk.drafSurat' => function($q) use ($userId) {
            $q->where('dibuat_oleh', $userId)->latest();
        }])->where('ke_user_id', $userId)->where('status', '!=', 'selesai')->get();

        $tugasBaru = 0;
        $sedangDikerjakan = 0;
        $menungguReview = 0;

        foreach ($allActiveDisposisi as $d) {
            $latestDraft = $d->suratMasuk->drafSurat->first();
            $isWaitingReview = $latestDraft && $latestDraft->status == 'menunggu_reviu';
            
            if ($isWaitingReview) {
                $menungguReview++;
            } elseif ($d->status == 'menunggu') {
                $tugasBaru++;
            } else {
                $sedangDikerjakan++;
            }
        }
            
        // Tugas Aktif (Priority / Sedang Dikerjakan)
        $tugasAktif = Disposisi::with('suratMasuk', 'pengirim')
            ->where('ke_user_id', $userId)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->orderBy('tenggat_waktu', 'asc')
            ->take(5)
            ->get();
            
        // Draft Terbaru
        $draftTerbaru = \App\Models\DrafSurat::with(['suratMasuk', 'reviuSurat' => function($q) {
            $q->latest();
        }])
        ->where('dibuat_oleh', $userId)
        ->latest()
        ->take(5)
        ->get();

        return view('staf.dashboard', compact(
            'tugasBaru', 'sedangDikerjakan', 'menungguReview', 
            'tugasAktif', 'draftTerbaru'
        ));
    }

    public function kepalaBiro()
    {
        return view('kabiro.dashboard');
    }
}
