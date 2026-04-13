<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TmdbService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $imageBaseUrl = 'https://image.tmdb.org/t/p/';

    public function __construct()
    {
        $this->baseUrl = config('services.tmdb.base_url');
        $this->apiKey = config('services.tmdb.api_key');
    }

    /**
     * Request ke TMDB API
     */
    protected function request(string $endpoint, array $params = []): ?array
    {
        $params['api_key'] = $this->apiKey;
        if (!isset($params['language'])) {
            $params['language'] = 'id-ID';
        }

        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}{$endpoint}", $params);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    /**
     * Request versi English (fallback kalau overview kosong)
     */
    protected function requestEnglish(string $endpoint, array $params = []): ?array
    {
        $params['language'] = 'en-US';
        return $this->request($endpoint, $params);
    }

    /**
     * Isi overview kosong di list film dengan fallback English
     */
    protected function fillOverviews(array $data): array
    {
        if (empty($data['results'])) {
            return $data;
        }

        $needsFallback = [];
        foreach ($data['results'] as $i => $movie) {
            if (empty($movie['overview'])) {
                $needsFallback[$i] = $movie['id'];
            }
        }

        if (!empty($needsFallback)) {
            foreach ($needsFallback as $i => $movieId) {
                $enData = $this->requestEnglish("/movie/{$movieId}");
                if ($enData && !empty($enData['overview'])) {
                    $data['results'][$i]['overview'] = $enData['overview'];
                }
            }
        }

        return $data;
    }

    /**
     * Popular Movies
     */
    public function getPopular(int $page = 1): ?array
    {
        $cacheKey = "tmdb_popular_{$page}";
        return Cache::remember($cacheKey, 3600, function () use ($page) {
            $data = $this->request('/movie/popular', ['page' => $page]);
            return $data ? $this->fillOverviews($data) : null;
        });
    }

    /**
     * Top Rated Movies
     */
    public function getTopRated(int $page = 1): ?array
    {
        $cacheKey = "tmdb_top_rated_{$page}";
        return Cache::remember($cacheKey, 3600, function () use ($page) {
            $data = $this->request('/movie/top_rated', ['page' => $page]);
            return $data ? $this->fillOverviews($data) : null;
        });
    }

    /**
     * Now Playing Movies
     */
    public function getNowPlaying(int $page = 1): ?array
    {
        $cacheKey = "tmdb_now_playing_{$page}";
        return Cache::remember($cacheKey, 3600, function () use ($page) {
            $data = $this->request('/movie/now_playing', ['page' => $page]);
            return $data ? $this->fillOverviews($data) : null;
        });
    }

    /**
     * Search Movies
     */
    public function search(string $query, int $page = 1): ?array
    {
        $data = $this->request('/search/movie', [
            'query' => $query,
            'page' => $page,
        ]);
        return $data ? $this->fillOverviews($data) : null;
    }

    /**
     * Discover Movies (filter by genre, tahun, dll)
     */
    public function discover(array $filters = [], int $page = 1): ?array
    {
        $params = ['page' => $page, 'sort_by' => $filters['sort_by'] ?? 'popularity.desc'];

        if (!empty($filters['genre'])) {
            $params['with_genres'] = $filters['genre'];
        }
        if (!empty($filters['year'])) {
            $params['primary_release_year'] = $filters['year'];
        }
        if (!empty($filters['vote_average_gte'])) {
            $params['vote_average.gte'] = $filters['vote_average_gte'];
        }

        $cacheKey = 'tmdb_discover_' . md5(json_encode($params));
        return Cache::remember($cacheKey, 1800, function () use ($params) {
            $data = $this->request('/discover/movie', $params);
            return $data ? $this->fillOverviews($data) : null;
        });
    }

    /**
     * Detail film berdasarkan TMDB ID
     */
    public function getMovieDetail(int $id): ?array
    {
        $cacheKey = "tmdb_movie_{$id}";
        return Cache::remember($cacheKey, 7200, function () use ($id) {
            $data = $this->request("/movie/{$id}", [
                'append_to_response' => 'credits,videos,similar',
            ]);
            if ($data && empty($data['overview'])) {
                $enData = $this->requestEnglish("/movie/{$id}");
                if ($enData && !empty($enData['overview'])) {
                    $data['overview'] = $enData['overview'];
                }
            }
            return $data;
        });
    }

    /**
     * List semua genre
     */
    public function getGenres(): ?array
    {
        return Cache::remember('tmdb_genres', 86400, function () {
            return $this->request('/genre/movie/list');
        });
    }

    /**
     * URL poster
     */
    public function posterUrl(?string $path, string $size = 'w500'): string
    {
        if ($path) {
            return $this->imageBaseUrl . $size . $path;
        }
        return '';
    }

    /**
     * URL backdrop
     */
    public function backdropUrl(?string $path, string $size = 'w1280'): string
    {
        if ($path) {
            return $this->imageBaseUrl . $size . $path;
        }
        return '';
    }
}
