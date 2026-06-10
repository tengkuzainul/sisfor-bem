<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProposalKegiatan extends Model
{
    use HasFactory;

    protected $table = 'proposal_kegiatan';

    protected $fillable = [
        'program_kerja_id',
        'user_id',
        'judul',
        'file_proposal',
        'catatan_pengaju',
        'status',
    ];

    /* ========================================
       Status Constants
       ======================================== */

    const STATUS_DIAJUKAN        = 'diajukan';
    const STATUS_REVIEW_PEMBINA  = 'review_pembina';
    const STATUS_REVISI_PEMBINA  = 'revisi_pembina';
    const STATUS_REVIEW_KAPRODI  = 'review_kaprodi';
    const STATUS_REVISI_KAPRODI  = 'revisi_kaprodi';
    const STATUS_DISETUJUI       = 'disetujui';
    const STATUS_DITOLAK         = 'ditolak';

    /* ========================================
       Relationships
       ======================================== */

    public function programKerja(): BelongsTo
    {
        return $this->belongsTo(ProgramKerja::class);
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProposalReview::class, 'proposal_kegiatan_id')->orderBy('created_at', 'desc');
    }

    /* ========================================
       Accessors
       ======================================== */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DIAJUKAN        => 'Diajukan',
            self::STATUS_REVIEW_PEMBINA  => 'Review Pembina',
            self::STATUS_REVISI_PEMBINA  => 'Revisi (Pembina)',
            self::STATUS_REVIEW_KAPRODI  => 'Review Pembina',
            self::STATUS_REVISI_KAPRODI  => 'Revisi (Pembina)',
            self::STATUS_DISETUJUI       => 'Disetujui',
            self::STATUS_DITOLAK         => 'Ditolak',
            default                      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DIAJUKAN                                    => '#3b82f6',
            self::STATUS_REVIEW_PEMBINA, self::STATUS_REVIEW_KAPRODI  => '#f59e0b',
            self::STATUS_REVISI_PEMBINA, self::STATUS_REVISI_KAPRODI  => '#ef4444',
            self::STATUS_DISETUJUI                                   => '#22c55e',
            self::STATUS_DITOLAK                                     => '#6b7280',
            default                                                  => '#6b7280',
        };
    }

    /**
     * Current review step label.
     */
    public function getStepLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DIAJUKAN, self::STATUS_REVIEW_PEMBINA, self::STATUS_REVISI_PEMBINA,
            self::STATUS_REVIEW_KAPRODI, self::STATUS_REVISI_KAPRODI                      => 'Tahap Pembina',
            self::STATUS_DISETUJUI                                                           => 'Selesai',
            self::STATUS_DITOLAK                                                             => 'Ditolak',
            default                                                                          => '-',
        };
    }

    /**
     * Check if pengurus can re-upload (revision requested).
     */
    public function canRevise(): bool
    {
        return in_array($this->status, [
            self::STATUS_REVISI_PEMBINA,
            self::STATUS_REVISI_KAPRODI,
        ]);
    }

    /**
     * Check if pembina can review this proposal.
     */
    public function canReviewByPembina(): bool
    {
        return in_array($this->status, [
            self::STATUS_DIAJUKAN,
            self::STATUS_REVIEW_PEMBINA,
            self::STATUS_REVIEW_KAPRODI,
        ]);
    }

    /**
     * Is finalized (approved or rejected)?
     */
    public function isFinalized(): bool
    {
        return in_array($this->status, [self::STATUS_DISETUJUI, self::STATUS_DITOLAK]);
    }
}
