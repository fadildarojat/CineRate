<?php

namespace App\Models;

// ============================================
// MODEL: Ulasan (Review)
// Merepresentasikan tabel 'ulasans' di database
// Menyimpan komentar/ulasan pengguna tentang film
// ============================================

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'film_id',
        'tmdb_id',
        'nama',
        'komentar',
    ];

    // ---- RELASI ----

    /**
     * Relasi: Ulasan milik satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Ulasan milik satu film
     */
    public function film()
    {
        return $this->belongsTo(Film::class);
    }
}
