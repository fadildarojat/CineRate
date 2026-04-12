<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Ulasan;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class TmdbController extends Controller
{
    protected TmdbService $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    /**
     * Home - Film Populer TMDB
     */
    public function home(Request $request)
    {
        $page = max(1, (int) $request->input('page', 1));
        $data = $this->tmdb->getPopular($page);
        $movies = $this->moveChineseTitleMoviesToBack($data['results'] ?? []);

        return view('home', [
            'movies'       => $movies,
            'currentPage'  => $data['page'] ?? 1,
            'totalPages'   => min($data['total_pages'] ?? 1, 500),
            'totalResults'  => $data['total_results'] ?? 0,
        ]);
    }

    /**
     * Pindahkan film dengan judul karakter Han (China) ke urutan paling belakang.
     */
    private function moveChineseTitleMoviesToBack(array $movies): array
    {
        $regular = [];
        $han = [];

        foreach ($movies as $movie) {
            if ($this->hasHanCharacterInTitle($movie)) {
                $han[] = $movie;
                continue;
            }

            $regular[] = $movie;
        }

        return array_merge($regular, $han);
    }

    /**
     * Cek apakah title/original_title mengandung karakter Han.
     */
    private function hasHanCharacterInTitle(array $movie): bool
    {
        $title = (string) ($movie['title'] ?? '');
        $originalTitle = (string) ($movie['original_title'] ?? '');

        return preg_match('/\\p{Han}/u', $title) === 1
            || preg_match('/\\p{Han}/u', $originalTitle) === 1;
    }

    /**
     * Popular Movies
     */
    public function popular(Request $request)
    {
        $page = max(1, (int) $request->input('page', 1));
        $data = $this->tmdb->getPopular($page);

        return view('tmdb.movies', [
            'title'       => 'Popular Movies',
            'icon'        => 'bi-fire',
            'movies'      => $data['results'] ?? [],
            'currentPage' => $data['page'] ?? 1,
            'totalPages'  => min($data['total_pages'] ?? 1, 500),
            'totalResults' => $data['total_results'] ?? 0,
            'routeName'   => 'tmdb.popular',
            'routeParams' => [],
        ]);
    }

    /**
     * Top Rated Movies
     */
    public function topRated(Request $request)
    {
        $page = max(1, (int) $request->input('page', 1));
        $data = $this->tmdb->getTopRated($page);

        return view('tmdb.movies', [
            'title'       => 'Top Rated Movies',
            'icon'        => 'bi-trophy',
            'movies'      => $data['results'] ?? [],
            'currentPage' => $data['page'] ?? 1,
            'totalPages'  => min($data['total_pages'] ?? 1, 500),
            'totalResults' => $data['total_results'] ?? 0,
            'routeName'   => 'tmdb.top-rated',
            'routeParams' => [],
        ]);
    }

    /**
     * Now Playing Movies
     */
    public function nowPlaying(Request $request)
    {
        $page = max(1, (int) $request->input('page', 1));
        $data = $this->tmdb->getNowPlaying($page);

        return view('tmdb.movies', [
            'title'       => 'Now Playing',
            'icon'        => 'bi-play-circle',
            'movies'      => $data['results'] ?? [],
            'currentPage' => $data['page'] ?? 1,
            'totalPages'  => min($data['total_pages'] ?? 1, 500),
            'totalResults' => $data['total_results'] ?? 0,
            'routeName'   => 'tmdb.now-playing',
            'routeParams' => [],
        ]);
    }

    /**
     * Search Movies
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $page = max(1, (int) $request->input('page', 1));
        $movies = [];
        $currentPage = 1;
        $totalPages = 1;
        $totalResults = 0;

        if ($query) {
            $data = $this->tmdb->search($query, $page);
            $movies = $data['results'] ?? [];
            $currentPage = $data['page'] ?? 1;
            $totalPages = min($data['total_pages'] ?? 1, 500);
            $totalResults = $data['total_results'] ?? 0;
        }

        return view('tmdb.search', [
            'query'        => $query,
            'movies'       => $movies,
            'currentPage'  => $currentPage,
            'totalPages'   => $totalPages,
            'totalResults' => $totalResults,
        ]);
    }

    /**
     * Discover Movies (filter by genre, tahun, dll)
     */
    public function discover(Request $request)
    {
        $page = max(1, (int) $request->input('page', 1));
        $filters = [
            'genre'   => $request->input('genre', ''),
            'year'    => $request->input('year', ''),
            'sort_by' => $request->input('sort_by', 'popularity.desc'),
        ];

        $data = $this->tmdb->discover($filters, $page);
        $genreData = $this->tmdb->getGenres();

        return view('tmdb.discover', [
            'movies'       => $data['results'] ?? [],
            'currentPage'  => $data['page'] ?? 1,
            'totalPages'   => min($data['total_pages'] ?? 1, 500),
            'totalResults' => $data['total_results'] ?? 0,
            'genres'       => $genreData['genres'] ?? [],
            'filters'      => $filters,
        ]);
    }

    /**
     * Detail film dari TMDB
     */
    public function detail(int $id)
    {
        $movie = $this->tmdb->getMovieDetail($id);

        if (!$movie) {
            abort(404, 'Film tidak ditemukan.');
        }

        // Hindari hard-fail saat koneksi DB belum siap di deploy awal.
        $ulasans = collect();
        try {
            $ulasans = Ulasan::where('tmdb_id', $id)->orderBy('created_at', 'desc')->get();
        } catch (\Throwable $e) {
            report($e);
        }

        return view('tmdb.detail', [
            'movie'        => $movie,
            'ulasans'      => $ulasans,
        ]);
    }

    /**
     * Simpan rating untuk film TMDB
     */
    public function simpanRating(Request $request, int $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:10',
        ]);

        try {
            Rating::create([
                'tmdb_id' => $id,
                'nama'    => $request->nama,
                'rating'  => $request->rating,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('tmdb.detail', $id)
                             ->withErrors(['rating' => 'Database belum siap. Coba lagi beberapa saat.'])
                             ->withInput();
        }

        return redirect()->route('tmdb.detail', $id)
                         ->with('sukses_rating', 'Rating berhasil disimpan!');
    }

    /**
     * Simpan ulasan untuk film TMDB
     */
    public function simpanUlasan(Request $request, int $id)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'komentar' => 'required|string|max:1000',
        ]);

        try {
            Ulasan::create([
                'tmdb_id'  => $id,
                'nama'     => $request->nama,
                'komentar' => $request->komentar,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('tmdb.detail', $id)
                             ->withErrors(['komentar' => 'Database belum siap. Coba lagi beberapa saat.'])
                             ->withInput();
        }

        return redirect()->route('tmdb.detail', $id)
                         ->with('sukses_ulasan', 'Ulasan berhasil ditambahkan!');
    }
}
