<?php

namespace App\Http\Controllers;

// ============================================
// CONTROLLER: StreamingController
// Menangani halaman streaming film
// Multi-source streaming embed (fallback)
// ============================================

use App\Models\Ulasan;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class StreamingController extends Controller
{
    protected TmdbService $tmdb;

    /**
     * Daftar sumber streaming embed (multi-source fallback)
     * Jika source pertama mati, user bisa switch ke source lain
     */
    protected array $streamingSources = [
        [
            'name'    => 'Server 1',
            'key'     => 'vidsrc-to',
            'urlTmdb' => 'https://vidsrc.to/embed/movie/{tmdb_id}',
            'urlImdb' => 'https://vidsrc.to/embed/movie/{imdb_id}',
        ],
        [
            'name'    => 'Server 2',
            'key'     => 'vidsrc-me',
            'urlTmdb' => 'https://vidsrc.xyz/embed/movie/{tmdb_id}',
            'urlImdb' => 'https://vidsrc.xyz/embed/movie/{imdb_id}',
        ],
        [
            'name'    => 'Server 3',
            'key'     => 'embed-su',
            'urlTmdb' => 'https://multiembed.mov/directstream.php?video_id={tmdb_id}&tmdb=1',
            'urlImdb' => 'https://multiembed.mov/directstream.php?video_id={imdb_id}',
        ],
    ];

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    /**
     * Halaman Streaming - Menonton film
     * URL: GET /watch/{id}
     * Membutuhkan autentikasi (middleware auth)
     */
    public function watch(Request $request, int $id)
    {
        // Ambil detail film dari TMDB
        $movie = $this->tmdb->getMovieDetail($id);

        if (!$movie) {
            abort(404, 'Film tidak ditemukan.');
        }

        // Ambil IMDB ID dari data TMDB (jika tersedia)
        $imdbId = $movie['imdb_id'] ?? null;

        // Tentukan source yang dipilih user (default: pertama)
        $selectedSource = $request->input('source', $this->streamingSources[0]['key']);

        // Build embed URL untuk semua source
        $sources = [];
        foreach ($this->streamingSources as $source) {
            $url = str_replace('{tmdb_id}', $id, $source['urlTmdb']);
            if ($imdbId) {
                $url = str_replace('{imdb_id}', $imdbId, $source['urlImdb']);
            }
            $sources[] = [
                'name'     => $source['name'],
                'key'      => $source['key'],
                'url'      => $url,
                'active'   => $source['key'] === $selectedSource,
            ];
        }

        // URL embed yang dipilih
        $activeSource = collect($sources)->firstWhere('active', true) ?? $sources[0];
        $embedUrl = $activeSource['url'];

        // Ambil ulasan dari database
        $ulasans = Ulasan::where('tmdb_id', $id)->orderBy('created_at', 'desc')->take(5)->get();

        return view('tmdb.watch', [
            'movie'    => $movie,
            'embedUrl' => $embedUrl,
            'sources'  => $sources,
            'ulasans'  => $ulasans,
        ]);
    }
}
