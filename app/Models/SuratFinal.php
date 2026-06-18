<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratFinal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function drafSurat()
    {
        return $this->belongsTo(DrafSurat::class);
    }

    public function penandatangan()
    {
        return $this->belongsTo(User::class, 'ditandatangani_oleh');
    }
}
