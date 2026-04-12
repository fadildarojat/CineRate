<?php

namespace App\Models;

// ============================================
// MODEL: User
// Merepresentasikan tabel 'users' di database
// Digunakan untuk autentikasi admin dan user
// ============================================

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    // Kolom yang disembunyikan saat serialisasi (keamanan)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting otomatis tipe data
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Password otomatis di-hash oleh Laravel
        ];
    }

    // ---- RELASI ----

    /**
     * Relasi: User memiliki banyak rating
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relasi: User memiliki banyak ulasan
     */
    public function ulasans()
    {
        return $this->hasMany(Ulasan::class);
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
