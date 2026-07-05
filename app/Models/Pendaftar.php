<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftar';

    const STATUS_MENDAFTAR = 'mendaftar';

    const STATUS_REVIEW = 'review';

    const STATUS_WAWANCARA = 'wawancara';

    const STATUS_DITERIMA = 'diterima';

    const STATUS_DITOLAK = 'ditolak';

    const STATUS_CADANGAN = 'cadangan';

    const STATUSES = [
        self::STATUS_MENDAFTAR => 'Mendaftar',
        self::STATUS_REVIEW => 'Dalam Review',
        self::STATUS_WAWANCARA => 'Wawancara',
        self::STATUS_DITERIMA => 'Diterima',
        self::STATUS_DITOLAK => 'Ditolak',
        self::STATUS_CADANGAN => 'Cadangan',
    ];

    protected $fillable = [
        'rekrutmen_id',
        'kode_pendaftaran',
        'nama_lengkap',
        'nim',
        'email',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'prodi',
        'angkatan',
        'alamat',
        'foto',
        'departemen_pilihan_1',
        'departemen_pilihan_2',
        'motivasi',
        'pengalaman_organisasi',
        'keahlian',
        'cv_file',
        'sertifikat_file',
        'link_portfolio',
        'status',
        'catatan_admin',
    ];

    protected $appends = ['status_label', 'status_color'];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    /* ========================================
       Boot — Auto-generate kode_pendaftaran
       ======================================== */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pendaftar) {
            if (empty($pendaftar->kode_pendaftaran)) {
                $pendaftar->kode_pendaftaran = 'REG-'.strtoupper(substr(uniqid(), -6));
            }
        });
    }

    /* ========================================
       Relationships
       ======================================== */

    public function rekrutmen(): BelongsTo
    {
        return $this->belongsTo(Rekrutmen::class);
    }

    public function departemenPilihan1(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_pilihan_1');
    }

    public function departemenPilihan2(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_pilihan_2');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(PendaftarReview::class)->orderByDesc('created_at');
    }

    /* ========================================
       Accessors
       ======================================== */

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_MENDAFTAR => '#6b7280',
            self::STATUS_REVIEW => '#8b5cf6',
            self::STATUS_WAWANCARA => '#f59e0b',
            self::STATUS_DITERIMA => '#10b981',
            self::STATUS_DITOLAK => '#ef4444',
            self::STATUS_CADANGAN => '#3b82f6',
            default => '#6b7280',
        };
    }

    /* ========================================
       Helpers
       ======================================== */

    public function getReviewCountByDepartemen(?int $departemenId): int
    {
        return $this->reviews()->where('departemen_id', $departemenId)->count();
    }

    public function getRekomendasi(): array
    {
        $reviews = $this->reviews()->where('tipe', 'rekomendasi')->get();

        return [
            'direkomendasikan' => $reviews->where('rekomendasi_status', 'direkomendasikan')->count(),
            'tidak_direkomendasikan' => $reviews->where('rekomendasi_status', 'tidak_direkomendasikan')->count(),
            'netral' => $reviews->where('rekomendasi_status', 'netral')->count(),
        ];
    }
}
