<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviuSurat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function drafSurat()
    {
        return $this->belongsTo(DrafSurat::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
