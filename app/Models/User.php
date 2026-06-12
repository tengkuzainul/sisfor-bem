<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /* ========================================
       Role Constants
       ======================================== */

    const ROLE_ADMINISTRATOR = 'administrator';
    const ROLE_PEMBINA       = 'pembina';
    const ROLE_PENGURUS      = 'pengurus';

    const ROLES = [
        self::ROLE_ADMINISTRATOR => 'Administrator',
        self::ROLE_PEMBINA       => 'Pembina',
        self::ROLE_PENGURUS      => 'Pengurus',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'anggota_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ========================================
       Relationships
       ======================================== */

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function proposalKegiatan(): HasMany
    {
        return $this->hasMany(ProposalKegiatan::class);
    }

    public function proposalReviews(): HasMany
    {
        return $this->hasMany(ProposalReview::class);
    }

    /* ========================================
       Role Helpers
       ======================================== */

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMINISTRATOR;
    }

    public function isPembina(): bool
    {
        return $this->role === self::ROLE_PEMBINA;
    }

    public function isPengurus(): bool
    {
        return $this->role === self::ROLE_PENGURUS;
    }

    public function hasRole(string|array $roles): bool
    {
        return is_array($roles) ? in_array($this->role, $roles) : $this->role === $roles;
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? ucfirst($this->role);
    }

    public function getRoleColorAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMINISTRATOR => '#ef4444',
            self::ROLE_PEMBINA       => '#f59e0b',
            self::ROLE_PENGURUS      => '#3b82f6',
            default                  => '#6b7280',
        };
    }
}
