<?php

namespace App\Observers;

use App\Models\DrafSurat;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SuratNotification;

class DrafSuratObserver
{
    public function created(DrafSurat $drafSurat): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Upload Draf Surat',
            'model_type' => DrafSurat::class,
            'model_id' => $drafSurat->id,
            'deskripsi' => "Draf surat (versi {$drafSurat->versi}) diunggah."
        ]);

        $disposisi = \App\Models\Disposisi::where('surat_masuk_id', $drafSurat->surat_masuk_id)
            ->where('ke_user_id', $drafSurat->dibuat_oleh)
            ->first();

        if ($disposisi && $disposisi->pengirim) {
            $disposisi->pengirim->notify(new SuratNotification(
                'Draft Baru Perlu Review',
                "Staf telah mengunggah draft baru (v{$drafSurat->versi}) dan menunggu review Anda.",
                route('kasubtim.draft.index')
            ));
        }
    }

    public function updated(DrafSurat $drafSurat): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update Draf Surat',
            'model_type' => DrafSurat::class,
            'model_id' => $drafSurat->id,
            'deskripsi' => "Draf surat diperbarui."
        ]);
    }
}
