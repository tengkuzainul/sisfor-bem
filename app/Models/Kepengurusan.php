<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Kepengurusan extends Model
{
    use HasFactory;

    protected $table = 'kepengurusan';

    protected $fillable = [
        'nama',
        'periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'visi',
        'misi',
        'is_active',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
            'is_active'       => 'boolean',
        ];
    }

    /* ========================================
       Relationships
       ======================================== */

    public function departemen(): HasMany
    {
        return $this->hasMany(Departemen::class);
    }

    public function keanggotaan(): HasMany
    {
        return $this->hasMany(Keanggotaan::class);
    }

    /* ========================================
       Scopes
       ======================================== */

    /**
     * Scope: hanya kepengurusan yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /* ========================================
       Helpers
       ======================================== */

    /**
     * Aktifkan kepengurusan ini & nonaktifkan lainnya.
     */
    public function activate(): void
    {
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
        static::flushCache();
    }

    /**
     * Nonaktifkan kepengurusan ini.
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
        static::flushCache();
    }

    /**
     * Ambil kepengurusan yang sedang aktif (cached 60 min).
     */
    public static function getActive(): ?self
    {
        return Cache::remember('kepengurusan_active', 3600, function () {
            return static::active()->first();
        });
    }

    /**
     * Flush semua cache terkait organisasi.
     */
    public static function flushCache(): void
    {
        Cache::forget('kepengurusan_active');
        Cache::forget('kepengurusan_list');
        Cache::forget('kepengurusan_index');
        Cache::forget('departemen_list');
        Cache::forget('jabatan_list');
        Cache::forget('jabatan_index');
    }

    /**
     * Hitung total anggota di kepengurusan ini.
     */
    public function getTotalAnggotaAttribute(): int
    {
        return $this->keanggotaan()->distinct('anggota_id')->count('anggota_id');
    }
}
