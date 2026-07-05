<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'user_id',
        'nama',
        'nim',
        'email',
        'no_hp',
        'jenis_kelamin',
        'angkatan',
        'prodi',
        'alamat',
        'foto',
    ];

    /* ========================================
       Relationships
       ======================================== */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function keanggotaan(): HasMany
    {
        return $this->hasMany(Keanggotaan::class);
    }

    /* ========================================
       Accessors
       ======================================== */

    public function getInisialAttribute(): string
    {
        $words = explode(' ', $this->nama);

        return strtoupper(
            substr($words[0], 0, 1).(isset($words[1]) ? substr($words[1], 0, 1) : '')
        );
    }

    /**
     * Get the active keanggotaan (based on eager-loaded or queried collection).
     */
    public function getActiveKeanggotaanAttribute()
    {
        return $this->keanggotaan->where('status', 'aktif')->first();
    }
}
