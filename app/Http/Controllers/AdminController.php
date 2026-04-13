<?php

namespace App\Http\Controllers;

// ============================================
// CONTROLLER: AdminController
// Menangani dashboard admin:
// - Menampilkan statistik rating & ulasan TMDB
// - Daftar rating & ulasan dari film TMDB
// ============================================

use App\Models\Rating;
use App\Models\Ulasan;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard Admin - Menampilkan statistik dan daftar rating & ulasan TMDB
     * URL: GET /admin/dashboard
     */
    public function dashboard(TmdbService $tmdb)
    {
        // Mengambil data statistik
        $totalRating = Rating::whereNotNull('tmdb_id')->count();
        $totalUlasan = Ulasan::whereNotNull('tmdb_id')->count();

        // Mengambil semua rating TMDB terbaru
        $ratings = Rating::whereNotNull('tmdb_id')
                         ->orderBy('created_at', 'desc')
                         ->get();

        // Mengambil semua ulasan TMDB terbaru
        $ulasans = Ulasan::whereNotNull('tmdb_id')
                         ->orderBy('created_at', 'desc')
                         ->get();

        // Kumpulkan semua TMDB ID unik dan ambil nama filmnya
        $tmdbIds = $ratings->pluck('tmdb_id')
            ->merge($ulasans->pluck('tmdb_id'))
            ->unique();

        $filmNames = [];
        foreach ($tmdbIds as $tmdbId) {
            $movie = $tmdb->getMovieDetail($tmdbId);
            $filmNames[$tmdbId] = $movie['title'] ?? 'Unknown';
        }

        return view('admin.dashboard', compact(
            'totalRating', 'totalUlasan', 'ratings', 'ulasans', 'filmNames'
        ));
    }

    /**
     * Hapus rating berdasarkan ID
     * URL: DELETE /admin/rating/{id}
     */
    public function deleteRating($id)
    {
        Rating::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard')->with('sukses', 'Rating berhasil dihapus!');
    }

    /**
     * Hapus ulasan berdasarkan ID
     * URL: DELETE /admin/ulasan/{id}
     */
    public function deleteUlasan($id)
    {
        Ulasan::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard')->with('sukses', 'Ulasan berhasil dihapus!');
    }
}
