<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    protected $table = 'disposisis';

    protected $fillable = [
        'surat_masuk_id',
        'dari_user_id',
        'ke_user_id',
        'instruksi',
        'catatan',
        'status',
        'tenggat_waktu',
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'ke_user_id');
    }

    public function dariUser()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function keUser()
    {
        return $this->belongsTo(User::class, 'ke_user_id');
    }
}
