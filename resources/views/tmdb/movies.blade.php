{{-- ============================================
    HALAMAN DAFTAR FILM TMDB
    Digunakan untuk: Popular, Top Rated, Now Playing
    ============================================ --}}

@extends('layouts.app')

@section('title', $title . ' - CineRate')

@section('content')

<h2 class="section-heading"><i class="bi {{ $icon }}"></i> {{ $title }}</h2>

<p class="mb-4" style="color: var(--imdb-text-muted);">
    Menampilkan halaman {{ $currentPage }} dari {{ number_format($totalPages) }}
    ({{ number_format($totalResults) }} film)
</p>

{{-- Grid Film --}}
<div class="row tmdb-grid">
    @forelse($movies as $movie)
        @include('tmdb._card', ['movie' => $movie])
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> Tidak ada film ditemukan.
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@include('tmdb._pagination', [
    'currentPage' => $currentPage,
    'totalPages'  => $totalPages,
    'prevUrl'     => route($routeName, array_merge($routeParams, ['page' => max(1, $currentPage - 1)])),
    'nextUrl'     => route($routeName, array_merge($routeParams, ['page' => min($totalPages, $currentPage + 1)])),
    'pageUrl'     => function($p) use ($routeName, $routeParams) {
        return route($routeName, array_merge($routeParams, ['page' => $p]));
    },
])

@endsection
