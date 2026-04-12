<?php

namespace App\Models;

// ============================================
// MODEL: Rating
// Merepresentasikan tabel 'ratings' di database
// Menyimpan nilai rating 1-5 dari pengguna
// ============================================

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'film_id',
        'tmdb_id',
        'nama',
        'rating',
    ];

    // ---- RELASI ----

    /**
     * Relasi: Rating milik satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Rating milik satu film
     */
    public function film()
    {
        return $this->belongsTo(Film::class);
    }
}
