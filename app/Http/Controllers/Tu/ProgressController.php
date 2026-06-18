<?php

namespace App\Http\Controllers\Tu;

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
        $drafSurats = DrafSurat::with(['suratMasuk', 'pembuat', 'suratFinal'])
            ->latest()
            ->paginate(10);
            
        $stats = [
            'total_aktif' => DrafSurat::whereDoesntHave('suratFinal', function ($q) {
                $q->where('status', 'terdistribusi');
            })->count(),
            'menunggu_ttd' => DrafSurat::where('status', 'selesai_direviu')->count(),
            'terdistribusi' => SuratFinal::where('status', 'terdistribusi')->whereMonth('created_at', now()->month)->count()
        ];
        
        $header = [
            'title' => 'Progress Surat',
            'subtitle' => 'Pantau status dan progress surat yang sedang berjalan'
        ];
        
        $role = 'tata_usaha';
        
        $riwayatSelesai = DrafSurat::with(['suratMasuk', 'pembuat', 'suratFinal'])
            ->whereHas('suratFinal', function ($q) {
                $q->where('status', 'terdistribusi');
            })
            ->latest()
            ->take(5)
            ->get();
        
        return view('tu.progress.index', compact('drafSurats', 'stats', 'header', 'role', 'riwayatSelesai'));
    }

    public function show($id)
    {
        $drafSurat = DrafSurat::with(['suratMasuk', 'pembuat.unitKerja', 'reviuSurat.user.unitKerja', 'suratFinal', 'reviuSurat' => function($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);
        
        $suratMasuk = $drafSurat->suratMasuk;
        
        $logs = collect();
        
        // Log Pembuatan Draf
        $logs->push((object)[
            'aksi' => 'Pembuatan Draf Surat',
            'deskripsi' => 'Draf surat berhasil dibuat',
            'created_at' => $drafSurat->created_at,
            'user' => $drafSurat->pembuat
        ]);
        
        // Log Review
        foreach($drafSurat->reviuSurat as $reviu) {
            $logs->push((object)[
                'aksi' => 'Reviu oleh ' . str_replace('_', ' ', ucwords($reviu->role_reviewer)),
                'deskripsi' => $reviu->catatan ?? 'Tidak ada catatan tambahan',
                'created_at' => $reviu->created_at,
                'user' => $reviu->user
            ]);
        }
        
        // Log Upload TTD
        if ($drafSurat->status === 'selesai_direviu' || $drafSurat->suratFinal) {
            $logs->push((object)[
                'aksi' => 'Proses Upload TTD',
                'deskripsi' => 'Draf surat disetujui dan menunggu TTD pimpinan',
                'created_at' => $drafSurat->updated_at,
                'user' => (object)['name' => 'Sistem', 'jabatan' => 'Auto-generated']
            ]);
        }
        
        // Log Terdistribusi
        if ($drafSurat->suratFinal && $drafSurat->suratFinal->status === 'terdistribusi') {
            $logs->push((object)[
                'aksi' => 'Distribusi Surat Final',
                'deskripsi' => 'Surat final telah ditandatangani dan didistribusikan',
                'created_at' => $drafSurat->suratFinal->updated_at,
                'user' => (object)['name' => 'TU Biro', 'jabatan' => 'Tata Usaha']
            ]);
        }
        
        $logs = $logs->sortByDesc('created_at')->values();
        
        return view('tu.progress.show', compact('drafSurat', 'suratMasuk', 'logs'));
    }
}