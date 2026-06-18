<?php

namespace App\Observers;

use App\Models\Disposisi;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SuratNotification;

class DisposisiObserver
{
    public function created(Disposisi $disposisi): void
    {
        $penerima = $disposisi->penerima->name ?? 'User';
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Disposisi Surat',
            'model_type' => Disposisi::class,
            'model_id' => $disposisi->id,
            'deskripsi' => "Surat didisposisikan kepada {$penerima} dengan instruksi: {$disposisi->instruksi}."
        ]);

        if ($disposisi->penerima) {
            $disposisi->penerima->notify(new SuratNotification(
                'Disposisi Baru',
                "Anda menerima disposisi baru dengan instruksi: {$disposisi->instruksi}",
                '#'
            ));
        }
    }

    public function updated(Disposisi $disposisi): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update Disposisi',
            'model_type' => Disposisi::class,
            'model_id' => $disposisi->id,
            'deskripsi' => "Status disposisi diperbarui menjadi {$disposisi->status}."
        ]);
    }
}
