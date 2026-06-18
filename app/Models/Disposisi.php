<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $guarded = [];

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
