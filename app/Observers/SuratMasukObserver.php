<?php

namespace App\Observers;

use App\Models\SuratMasuk;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\SuratNotification;

class SuratMasukObserver
{
    public function created(SuratMasuk $suratMasuk): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Input Surat Masuk',
            'model_type' => SuratMasuk::class,
            'model_id' => $suratMasuk->id,
            'deskripsi' => "Surat masuk baru dengan nomor: {$suratMasuk->nomor_surat} ditambahkan."
        ]);

        // Notify TU Biro
        $tus = User::where('role', 'tata_usaha')->get();
        foreach ($tus as $tu) {
            $tu->notify(new SuratNotification(
                'Surat Masuk Baru',
                "Surat baru nomor {$suratMasuk->nomor_surat} dari {$suratMasuk->asal_surat} perlu didisposisi.",
                route('tu.surat-masuk.index')
            ));
        }
    }

    public function updated(SuratMasuk $suratMasuk): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update Surat Masuk',
            'model_type' => SuratMasuk::class,
            'model_id' => $suratMasuk->id,
            'deskripsi' => "Status atau data surat masuk {$suratMasuk->nomor_surat} diperbarui menjadi {$suratMasuk->status}."
        ]);
    }

    public function deleted(SuratMasuk $suratMasuk): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus Surat Masuk',
            'model_type' => SuratMasuk::class,
            'model_id' => $suratMasuk->id,
            'deskripsi' => "Surat masuk dengan nomor {$suratMasuk->nomor_surat} dihapus."
        ]);
    }
}
