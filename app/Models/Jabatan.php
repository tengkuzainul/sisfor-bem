<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    protected $fillable = [
        'nama',
        'level',
        'deskripsi',
    ];

    /* ========================================
       Relationships
       ======================================== */

    public function keanggotaan(): HasMany
    {
        return $this->hasMany(Keanggotaan::class);
    }

    /* ========================================
       Scopes
       ======================================== */

    public function scopeOrdered($query)
    {
        return $query->orderBy('level');
    }
}
