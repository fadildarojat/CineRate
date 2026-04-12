{{-- ============================================
    HALAMAN DISCOVER FILM TMDB
    Filter by genre, tahun, sort
    ============================================ --}}

@extends('layouts.app')

@section('title', 'Discover Film - CineRate')

@section('content')

<h2 class="section-heading"><i class="bi bi-compass"></i> Discover Film</h2>

{{-- Form Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('tmdb.discover') }}" method="GET">
            <div class="row g-3 align-items-end discover-filter-grid">

                {{-- Genre --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-tags"></i> Genre</label>
                    <select name="genre" class="form-select">
                        <option value="">Semua Genre</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre['id'] }}"
                                {{ ($filters['genre'] ?? '') == $genre['id'] ? 'selected' : '' }}>
                                {{ $genre['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tahun --}}
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-calendar"></i> Tahun</label>
                    <select name="year" class="form-select">
                        <option value="">Semua Tahun</option>
                        @for($y = date('Y'); $y >= 1970; $y--)
                            <option value="{{ $y }}"
                                {{ ($filters['year'] ?? '') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Sort --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-sort-down"></i> Urutkan</label>
                    <select name="sort_by" class="form-select">
                        <option value="popularity.desc" {{ ($filters['sort_by'] ?? '') == 'popularity.desc' ? 'selected' : '' }}>Paling Populer</option>
                        <option value="vote_average.desc" {{ ($filters['sort_by'] ?? '') == 'vote_average.desc' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="primary_release_date.desc" {{ ($filters['sort_by'] ?? '') == 'primary_release_date.desc' ? 'selected' : '' }}>Terbaru</option>
                        <option value="revenue.desc" {{ ($filters['sort_by'] ?? '') == 'revenue.desc' ? 'selected' : '' }}>Pendapatan Tertinggi</option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-imdb w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('tmdb.discover') }}" class="btn btn-outline-imdb w-100">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

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
                <i class="bi bi-info-circle"></i> Tidak ada film ditemukan dengan filter ini.
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@include('tmdb._pagination', [
    'currentPage' => $currentPage,
    'totalPages'  => $totalPages,
    'prevUrl'     => route('tmdb.discover', array_merge($filters, ['page' => max(1, $currentPage - 1)])),
    'nextUrl'     => route('tmdb.discover', array_merge($filters, ['page' => min($totalPages, $currentPage + 1)])),
    'pageUrl'     => function($p) use ($filters) {
        return route('tmdb.discover', array_merge($filters, ['page' => $p]));
    },
])

@endsection
