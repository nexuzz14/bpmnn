<?php

namespace App\Observers;

use App\Models\ReviuSurat;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\SuratNotification;

class ReviuSuratObserver
{
    public function created(ReviuSurat $reviuSurat): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Pengajuan Reviu Draf',
            'model_type' => ReviuSurat::class,
            'model_id' => $reviuSurat->id,
            'deskripsi' => "Draf surat diajukan untuk reviu tingkat {$reviuSurat->tingkat}."
        ]);
    }

    public function updated(ReviuSurat $reviuSurat): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tindak Lanjut Reviu',
            'model_type' => ReviuSurat::class,
            'model_id' => $reviuSurat->id,
            'deskripsi' => "Reviu tingkat {$reviuSurat->tingkat} diselesaikan dengan status: {$reviuSurat->status}."
        ]);

        if ($reviuSurat->status === 'revisi') {
            // Notify Staf (creator of DrafSurat)
            if ($reviuSurat->drafSurat && $reviuSurat->drafSurat->pembuat) {
                $reviuSurat->drafSurat->pembuat->notify(new SuratNotification(
                    'Revisi Draf Surat',
                    "Draf Surat untuk nomor {$reviuSurat->drafSurat->suratMasuk->nomor_surat} dikembalikan untuk direvisi.",
                    route('staf.draf-surat.index')
                ));
            }
        } elseif ($reviuSurat->status === 'disetujui') {
            if ($reviuSurat->tingkat === '1') {
                // Notify Kabag
                $kabags = User::where('role', 'kepala_bagian')->get();
                foreach ($kabags as $kabag) {
                    $kabag->notify(new SuratNotification(
                        'Review Tingkat 2',
                        "Draf surat baru telah lulus Review Tingkat 1 dan menunggu review Anda.",
                        route('kabag.review.index')
                    ));
                }
            } elseif ($reviuSurat->tingkat == '2' && $reviuSurat->status == 'disetujui') {
                $kabiro = User::role('kepala_biro')->first();
                if ($kabiro) {
                    $kabiro->notify(new SuratNotification(
                        'Persetujuan Final Draf',
                        "Draf surat baru telah lulus Review Tingkat 2 dan menunggu persetujuan akhir.",
                        route('kabiro.review-final.index')
                    ));
                }
            } elseif ($reviuSurat->tingkat === 'final') {
                // Notify TU to Upload TTD
                $tus = User::where('role', 'tata_usaha')->get();
                foreach ($tus as $tu) {
                    $tu->notify(new SuratNotification(
                        'Upload Surat Final (TTD)',
                        "Surat telah disetujui Kepala Biro. Silakan upload scan surat yang sudah di TTD.",
                        route('tu.upload-ttd.index')
                    ));
                }
            }
        }
    }
}
