{{-- ============================================
    HALAMAN HOME - Film Populer TMDB
    Menampilkan film populer dari TMDB
    beserta rating TMDB (IMDb Style)
    ============================================ --}}

@extends('layouts.app')

@section('title', 'CineRate - Rating & Review Film')

@section('content')

{{-- Hero Section --}}
<div class="hero-section">
    <div class="container">
        <h1>CineRate</h1>
        <p>Temukan film favoritmu, berikan rating, dan tulis ulasanmu!</p>
    </div>
</div>

{{-- Judul Section --}}
<h2 class="section-heading"><i class="bi bi-fire"></i> Film Populer</h2>

<p class="mb-4" style="color: var(--imdb-text-muted);">
    Menampilkan halaman {{ $currentPage }} dari {{ number_format($totalPages) }}
    ({{ number_format($totalResults) }} film)
</p>

{{-- Grid Kartu Film --}}
<div class="row tmdb-grid">
    @forelse($movies as $movie)
        @include('tmdb._card', ['movie' => $movie])
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> Tidak ada film yang tersedia.
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@include('tmdb._pagination', [
    'currentPage' => $currentPage,
    'totalPages'  => $totalPages,
    'prevUrl'     => route('home', ['page' => max(1, $currentPage - 1)]),
    'nextUrl'     => route('home', ['page' => min($totalPages, $currentPage + 1)]),
    'pageUrl'     => function($p) {
        return route('home', ['page' => $p]);
    },
])

@endsection
