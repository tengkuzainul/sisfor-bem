<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keanggotaan extends Model
{
    use HasFactory;

    protected $table = 'keanggotaan';

    protected $fillable = [
        'kepengurusan_id',
        'anggota_id',
        'departemen_id',
        'jabatan_id',
        'status',
        'tanggal_bergabung',
        'tanggal_selesai',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bergabung' => 'date',
            'tanggal_selesai'   => 'date',
        ];
    }

    /* ========================================
       Relationships
       ======================================== */

    public function kepengurusan(): BelongsTo
    {
        return $this->belongsTo(Kepengurusan::class);
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    /* ========================================
       Scopes
       ======================================== */

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeForActiveKepengurusan($query)
    {
        return $query->whereHas('kepengurusan', fn ($q) => $q->active());
    }
}
