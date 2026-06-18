<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'parent_id',
        'level',
        'deskripsi',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'unit_kerja_id');
    }
}
