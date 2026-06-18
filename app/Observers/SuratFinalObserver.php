<?php

namespace App\Observers;

use App\Models\SuratFinal;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SuratNotification;

class SuratFinalObserver
{
    public function created(SuratFinal $suratFinal): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Persetujuan Final (TTE)',
            'model_type' => SuratFinal::class,
            'model_id' => $suratFinal->id,
            'deskripsi' => "Surat final berhasil ditandatangani dan siap didistribusikan."
        ]);
    }

    public function updated(SuratFinal $suratFinal): void
    {
        if ($suratFinal->isDirty('status') && $suratFinal->status === 'sudah') {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Distribusi Surat Final',
                'model_type' => SuratFinal::class,
                'model_id' => $suratFinal->id,
                'deskripsi' => "Surat final telah didistribusikan."
            ]);

            // Notify Staf (creator of DrafSurat)
            if ($suratFinal->reviuSurat && $suratFinal->reviuSurat->drafSurat && $suratFinal->reviuSurat->drafSurat->pembuat) {
                $suratFinal->reviuSurat->drafSurat->pembuat->notify(new SuratNotification(
                    'Surat Final Didistribusikan',
                    "Surat dari draft Anda telah resmi didistribusikan.",
                    route('staf.selesai.index')
                ));
            }
        }
    }
}
