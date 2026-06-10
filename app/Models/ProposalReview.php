<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalReview extends Model
{
    use HasFactory;

    protected $table = 'proposal_review';

    protected $fillable = [
        'proposal_kegiatan_id',
        'user_id',
        'aksi',
        'komentar',
        'file_lampiran',
    ];

    /* ========================================
       Relationships
       ======================================== */

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(ProposalKegiatan::class, 'proposal_kegiatan_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /* ========================================
       Accessors
       ======================================== */

    public function getAksiLabelAttribute(): string
    {
        return match ($this->aksi) {
            'komentar' => 'Komentar',
            'revisi'   => 'Minta Revisi',
            'approve'  => 'Approve',
            'tolak'    => 'Tolak',
            default    => ucfirst($this->aksi),
        };
    }

    public function getAksiColorAttribute(): string
    {
        return match ($this->aksi) {
            'komentar' => '#3b82f6',
            'revisi'   => '#f59e0b',
            'approve'  => '#22c55e',
            'tolak'    => '#ef4444',
            default    => '#6b7280',
        };
    }
}
