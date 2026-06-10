<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Rekrutmen extends Model
{
    use HasFactory;

    protected $table = 'rekrutmen';

    const STATUS_DRAFT   = 'draft';
    const STATUS_DIBUKA  = 'dibuka';
    const STATUS_DITUTUP = 'ditutup';
    const STATUS_SELESAI = 'selesai';

    const STATUSES = [
        self::STATUS_DRAFT   => 'Draft',
        self::STATUS_DIBUKA  => 'Dibuka',
        self::STATUS_DITUTUP => 'Ditutup',
        self::STATUS_SELESAI => 'Selesai',
    ];

    protected $fillable = [
        'kepengurusan_id',
        'judul',
        'slug',
        'deskripsi',
        'persyaratan',
        'tanggal_mulai',
        'tanggal_berakhir',
        'poster',
        'status',
    ];

    protected $appends = ['status_label', 'status_color', 'is_open'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'    => 'date',
            'tanggal_berakhir' => 'date',
        ];
    }

    /* ========================================
       Boot — Auto-generate slug
       ======================================== */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rekrutmen) {
            if (empty($rekrutmen->slug)) {
                $rekrutmen->slug = Str::slug($rekrutmen->judul) . '-' . Str::random(6);
            }
        });
    }

    /* ========================================
       Relationships
       ======================================== */

    public function kepengurusan(): BelongsTo
    {
        return $this->belongsTo(Kepengurusan::class);
    }

    public function pendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class);
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
            self::STATUS_DRAFT   => '#6b7280',
            self::STATUS_DIBUKA  => '#10b981',
            self::STATUS_DITUTUP => '#f59e0b',
            self::STATUS_SELESAI => '#3b82f6',
            default              => '#6b7280',
        };
    }

    public function getIsOpenAttribute(): bool
    {
        return $this->status === self::STATUS_DIBUKA
            && now()->gte($this->tanggal_mulai)
            && now()->lte($this->tanggal_berakhir);
    }

    /* ========================================
       Scopes
       ======================================== */

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_DIBUKA)
                     ->where('tanggal_mulai', '<=', now())
                     ->where('tanggal_berakhir', '>=', now());
    }
}
