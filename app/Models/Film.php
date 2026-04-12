<?php

namespace App\Models;

// ============================================
// MODEL: Film
// Merepresentasikan tabel 'films' di database
// Menyimpan data film beserta relasinya
// ============================================

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'judul',
        'tahun',
        'genre',
        'sinopsis',
        'poster',
    ];

    // ---- RELASI ----

    /**
     * Relasi: Film memiliki banyak rating
     * Banyak user bisa memberi rating ke satu film
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relasi: Film memiliki banyak ulasan
     */
    public function ulasans()
    {
        return $this->hasMany(Ulasan::class);
    }

    // ---- ACCESSOR (Fungsi Tambahan) ----

    /**
     * Menghitung rata-rata rating film
     * Dipanggil dengan: $film->rata_rating
     */
    public function getRataRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * Menghitung jumlah rating film
     * Dipanggil dengan: $film->jumlah_rating
     */
    public function getJumlahRatingAttribute()
    {
        return $this->ratings()->count();
    }
}
