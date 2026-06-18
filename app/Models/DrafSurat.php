<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrafSurat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function getPenugasanAttribute()
    {
        return \App\Models\Disposisi::where('surat_masuk_id', $this->surat_masuk_id)
            ->where('ke_user_id', $this->dibuat_oleh)
            ->first();
    }

    public function reviuTerkini()
    {
        return $this->hasOne(ReviuSurat::class)->latestOfMany();
    }


    public function reviuSurat()
    {
        return $this->hasMany(ReviuSurat::class);
    }

    public function suratFinal()
    {
        return $this->hasOne(SuratFinal::class);
    }
}
