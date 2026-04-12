{{-- ============================================
    HALAMAN SEARCH FILM TMDB
    ============================================ --}}

@extends('layouts.app')

@section('title', 'Search Film - CineRate')

@section('content')

<h2 class="section-heading"><i class="bi bi-search"></i> Cari Film</h2>

{{-- Form Pencarian --}}
<div class="mb-4">
    <form action="{{ route('tmdb.search') }}" method="GET">
        <div class="input-group input-group-lg search-form-group">
            <input type="text" name="q" class="form-control"
                   placeholder="Ketik judul film..." value="{{ $query }}" autofocus>
            <button class="btn btn-imdb" type="submit">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
    </form>
</div>

@if($query)
    <p class="mb-4" style="color: var(--imdb-text-muted);">
        Hasil pencarian "<strong style="color: var(--imdb-yellow);">{{ $query }}</strong>"
        — {{ number_format($totalResults) }} film ditemukan
        @if($totalPages > 1)
            (halaman {{ $currentPage }} dari {{ number_format($totalPages) }})
        @endif
    </p>

    {{-- Grid Film --}}
    <div class="row tmdb-grid">
        @forelse($movies as $movie)
            @include('tmdb._card', ['movie' => $movie])
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Tidak ada film ditemukan untuk "{{ $query }}".
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @include('tmdb._pagination', [
        'currentPage' => $currentPage,
        'totalPages'  => $totalPages,
        'prevUrl'     => route('tmdb.search', ['q' => $query, 'page' => max(1, $currentPage - 1)]),
        'nextUrl'     => route('tmdb.search', ['q' => $query, 'page' => min($totalPages, $currentPage + 1)]),
        'pageUrl'     => function($p) use ($query) {
            return route('tmdb.search', ['q' => $query, 'page' => $p]);
        },
    ])
@else
    <div class="text-center py-5">
        <i class="bi bi-search" style="font-size: 4rem; color: var(--imdb-dark-5);"></i>
        <p class="mt-3" style="color: var(--imdb-text-muted);">Ketik judul film untuk mulai mencari</p>
    </div>
@endif

@endsection
