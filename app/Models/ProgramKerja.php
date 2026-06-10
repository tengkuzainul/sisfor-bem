<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramKerja extends Model
{
    use HasFactory;

    protected $table = 'program_kerja';

    protected $fillable = [
        'kepengurusan_id',
        'kategori_proker_id',
        'departemen_id',
        'nama',
        'deskripsi',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    /* ========================================
       Relationships
       ======================================== */

    public function kepengurusan(): BelongsTo
    {
        return $this->belongsTo(Kepengurusan::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriProker::class, 'kategori_proker_id');
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    public function dokumentasi(): HasMany
    {
        return $this->hasMany(DokumentasiProker::class);
    }

    /* ========================================
       Accessors
       ======================================== */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'coming_soon' => 'Coming Soon',
            'berlangsung' => 'Berlangsung',
            'pending'     => 'Pending / Undur',
            'selesai'     => 'Selesai',
            default       => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'coming_soon' => 'info',
            'berlangsung' => 'warning',
            'pending'     => 'danger',
            'selesai'     => 'success',
            default       => 'gray',
        };
    }

    /* ========================================
       Scopes
       ======================================== */

    public function scopeForActiveKepengurusan($query)
    {
        return $query->whereHas('kepengurusan', fn ($q) => $q->active());
    }
}
