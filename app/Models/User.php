<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Role constants
     */
    const ROLE_PIMPINAN = 0;
    const ROLE_ADMIN = 1;
    const ROLE_ADMIN_INSTANSI = 2;
    const ROLE_USER = 3;
    const ROLE_KABID = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'integer',
        ];
    }

    /**
     * Check if user is Pimpinan
     */
    public function isPimpinan(): bool
    {
        return $this->role === self::ROLE_PIMPINAN;
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is Admin Instansi
     */
    public function isAdminInstansi(): bool
    {
        return $this->role === self::ROLE_ADMIN_INSTANSI;
    }

    /**
     * Check if user is User Biasa
     */
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Check if user is Kepala Bidang (Kabid)
     */
    public function isKabid(): bool
    {
        return $this->role === self::ROLE_KABID;
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_PIMPINAN => 'Pimpinan',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_ADMIN_INSTANSI => 'Admin Instansi',
            self::ROLE_USER => 'User',
            self::ROLE_KABID => 'Kepala Bidang',
            default => 'Unknown',
        };
    }
}
