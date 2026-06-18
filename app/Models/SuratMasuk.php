<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function disposisi()
    {
        return $this->hasMany(Disposisi::class);
    }

    public function drafSurat()
    {
        return $this->hasMany(DrafSurat::class);
    }
}
