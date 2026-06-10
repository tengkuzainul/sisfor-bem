<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftarReview extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_review';

    const TIPE_SARAN       = 'saran';
    const TIPE_KRITIK      = 'kritik';
    const TIPE_REKOMENDASI = 'rekomendasi';

    const TIPE_LABELS = [
        self::TIPE_SARAN       => 'Saran',
        self::TIPE_KRITIK      => 'Kritik',
        self::TIPE_REKOMENDASI => 'Rekomendasi',
    ];

    const REKOMENDASI_DIREKOMENDASIKAN       = 'direkomendasikan';
    const REKOMENDASI_TIDAK_DIREKOMENDASIKAN = 'tidak_direkomendasikan';
    const REKOMENDASI_NETRAL                 = 'netral';

    const REKOMENDASI_LABELS = [
        self::REKOMENDASI_DIREKOMENDASIKAN       => 'Direkomendasikan',
        self::REKOMENDASI_TIDAK_DIREKOMENDASIKAN => 'Tidak Direkomendasikan',
        self::REKOMENDASI_NETRAL                 => 'Netral',
    ];

    protected $fillable = [
        'pendaftar_id',
        'user_id',
        'departemen_id',
        'tipe',
        'komentar',
        'rekomendasi_status',
    ];

    /* ========================================
       Relationships
       ======================================== */

    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    /* ========================================
       Accessors
       ======================================== */

    public function getTipeLabelAttribute(): string
    {
        return self::TIPE_LABELS[$this->tipe] ?? ucfirst($this->tipe);
    }

    public function getTipeColorAttribute(): string
    {
        return match ($this->tipe) {
            self::TIPE_SARAN       => '#3b82f6',
            self::TIPE_KRITIK      => '#ef4444',
            self::TIPE_REKOMENDASI => '#10b981',
            default                => '#6b7280',
        };
    }

    public function getRekomendasiLabelAttribute(): ?string
    {
        if (!$this->rekomendasi_status) return null;
        return self::REKOMENDASI_LABELS[$this->rekomendasi_status] ?? ucfirst($this->rekomendasi_status);
    }

    public function getRekomendasiColorAttribute(): ?string
    {
        if (!$this->rekomendasi_status) return null;
        return match ($this->rekomendasi_status) {
            self::REKOMENDASI_DIREKOMENDASIKAN       => '#10b981',
            self::REKOMENDASI_TIDAK_DIREKOMENDASIKAN => '#ef4444',
            self::REKOMENDASI_NETRAL                 => '#f59e0b',
            default                                  => '#6b7280',
        };
    }
}
