<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemen';

    protected $fillable = [
        'kepengurusan_id',
        'nama',
        'singkatan',
        'deskripsi',
    ];

    /* ========================================
       Relationships
       ======================================== */

    public function kepengurusan(): BelongsTo
    {
        return $this->belongsTo(Kepengurusan::class);
    }

    public function keanggotaan(): HasMany
    {
        return $this->hasMany(Keanggotaan::class);
    }

    /* ========================================
       Accessors
       ======================================== */

    public function getJumlahAnggotaAttribute(): int
    {
        return $this->keanggotaan()->count();
    }
}
