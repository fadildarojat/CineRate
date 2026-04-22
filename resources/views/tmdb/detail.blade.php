{{-- ============================================
    HALAMAN DETAIL FILM TMDB
    Menampilkan info lengkap film dari TMDB API
    + Tombol streaming + Form rating & ulasan (harus login)
    ============================================ --}}

@extends('layouts.app')

@section('title', ($movie['title'] ?? 'Detail Film') . ' - CineRate')

@section('content')

{{-- Tombol Kembali --}}
<a href="{{ url()->previous() }}" class="btn btn-outline-imdb mb-4">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

{{-- Detail Film --}}
<div class="row mb-5">
    {{-- Poster --}}
    <div class="col-md-4 mb-3">
        @if(!empty($movie['poster_path']))
            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                 class="detail-poster" alt="{{ $movie['title'] }}">
        @else
            <div class="detail-poster no-poster d-flex align-items-center justify-content-center"
                 style="height: 500px; border-radius: 8px;">
                <i class="bi bi-film" style="font-size: 5rem;"></i>
            </div>
        @endif

        {{-- Tombol Tonton Sekarang --}}
        <a href="{{ route('streaming.watch', $movie['id']) }}" class="btn btn-streaming w-100 mt-3" id="btn-watch">
            <i class="bi bi-play-circle-fill"></i> Tonton Sekarang
        </a>
    </div>

    {{-- Info Film --}}
    <div class="col-md-8">
        <div class="detail-info">
            <h1>{{ $movie['title'] }}</h1>

            <p class="mb-3" style="color: var(--imdb-text-muted);">
                <i class="bi bi-calendar"></i>
                {{ !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '-' }}
                @if(!empty($movie['genres']))
                    &nbsp;|&nbsp;
                    @foreach($movie['genres'] as $genre)
                        <span class="badge badge-genre">{{ $genre['name'] }}</span>
                    @endforeach
                @endif
                @if(!empty($movie['runtime']))
                    &nbsp;|&nbsp;
                    <i class="bi bi-clock"></i> {{ floor($movie['runtime'] / 60) }}j {{ $movie['runtime'] % 60 }}m
                @endif
            </p>

            {{-- Box Rating TMDB --}}
            <div class="rating-box mb-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-star-fill" style="color: var(--imdb-yellow); font-size: 1.5rem;"></i>
                    <span class="rating-number">{{ number_format($movie['vote_average'] ?? 0, 1) }}</span>
                    <span style="color: var(--imdb-text-muted); font-size: 1.2rem;">/10</span>
                </div>
                <div class="rating-text">TMDB - dari {{ number_format($movie['vote_count'] ?? 0) }} vote</div>
            </div>

            <h5 class="fw-bold" style="color: var(--imdb-yellow);">Sinopsis</h5>
            <p style="color: var(--imdb-text); line-height: 1.7;">
                {{ $movie['overview'] ?: 'Sinopsis tidak tersedia.' }}
            </p>

            {{-- Tombol Tonton (versi desktop, di info section) --}}
            <a href="{{ route('streaming.watch', $movie['id']) }}" class="btn btn-streaming btn-lg mt-2 d-none d-md-inline-flex">
                <i class="bi bi-play-circle-fill"></i> Tonton Sekarang
            </a>
        </div>
    </div>
</div>

<hr style="border-color: var(--imdb-dark-4);">

{{-- ============================================ --}}
{{-- FORM RATING DAN ULASAN                       --}}
{{-- Harus login untuk memberi rating & ulasan    --}}
{{-- ============================================ --}}
<div class="row mt-4" id="ulasan">
    @auth
        {{-- Form Rating --}}
        <div class="col-md-6 mb-4">
            <div class="form-rating">
                <h4 class="fw-bold mb-3" style="color: var(--imdb-yellow);">
                    <i class="bi bi-star"></i> Beri Rating
                </h4>

                @if(session('sukses_rating'))
                    <div class="alert alert-success alert-auto-hide">{{ session('sukses_rating') }}</div>
                @endif

                @if($errors->has('rating'))
                    <div class="alert alert-danger alert-auto-hide">
                        {{ $errors->first('rating') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('tmdb.rating', $movie['id']) }}">
                    @csrf
                    <div class="mb-3">
                        <p style="color: var(--imdb-text-muted); font-size: 0.9rem;">
                            <i class="bi bi-person-circle"></i> Rating sebagai 
                            <strong style="color: var(--imdb-yellow);">{{ Auth::user()->username }}</strong>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rating (1-10)</label>
                        <div>
                            @for($i = 1; $i <= 10; $i++)
                                <span class="star-interactive" onclick="pilihRating({{ $i }})" style="cursor: pointer;">
                                    <i class="bi bi-star"></i>
                                </span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="input-rating" value="0">
                    </div>

                    <button type="submit" class="btn btn-imdb">
                        <i class="bi bi-send"></i> Kirim Rating
                    </button>
                </form>
            </div>
        </div>

        {{-- Form Ulasan --}}
        <div class="col-md-6 mb-4">
            <div class="form-rating">
                <h4 class="fw-bold mb-3" style="color: var(--imdb-yellow);">
                    <i class="bi bi-chat-dots"></i> Tulis Ulasan
                </h4>

                @if(session('sukses_ulasan'))
                    <div class="alert alert-success alert-auto-hide">{{ session('sukses_ulasan') }}</div>
                @endif

                <form method="POST" action="{{ route('tmdb.ulasan', $movie['id']) }}">
                    @csrf
                    <div class="mb-3">
                        <p style="color: var(--imdb-text-muted); font-size: 0.9rem;">
                            <i class="bi bi-person-circle"></i> Ulasan sebagai 
                            <strong style="color: var(--imdb-yellow);">{{ Auth::user()->username }}</strong>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar</label>
                        <textarea class="form-control" name="komentar" id="komentar" rows="4"
                                  placeholder="Tulis ulasan Anda tentang film ini..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-imdb">
                        <i class="bi bi-send"></i> Kirim Ulasan
                    </button>
                </form>
            </div>
        </div>
    @else
        {{-- Pesan Login Required --}}
        <div class="col-12 mb-4">
            <div class="form-rating text-center py-5">
                <i class="bi bi-lock" style="font-size: 3rem; color: var(--imdb-yellow); display: block; margin-bottom: 1rem;"></i>
                <h4 class="fw-bold mb-3" style="color: #fff;">Login untuk Memberi Rating & Ulasan</h4>
                <p style="color: var(--imdb-text-muted); margin-bottom: 1.5rem;">
                    Kamu harus login terlebih dahulu untuk bisa memberikan rating dan menulis ulasan film ini.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('login') }}" class="btn btn-imdb">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-imdb">
                        <i class="bi bi-person-plus"></i> Daftar
                    </a>
                </div>
            </div>
        </div>
    @endauth
</div>

<hr style="border-color: var(--imdb-dark-4);">

{{-- ============================================ --}}
{{-- DAFTAR ULASAN                                --}}
{{-- ============================================ --}}
<h4 class="section-heading mt-4">
    <i class="bi bi-chat-square-text"></i> Ulasan Pengguna ({{ $ulasans->count() }})
</h4>

@forelse($ulasans as $ulasan)
    <div class="ulasan-item">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="username">
                <i class="bi bi-person-circle"></i>
                {{ $ulasan->nama ?? 'Anonim' }}
            </span>
            <span class="tanggal">
                <i class="bi bi-clock"></i>
                {{ $ulasan->created_at->format('d M Y, H:i') }}
            </span>
        </div>
        <p class="mb-0">{!! nl2br(e($ulasan->komentar)) !!}</p>
    </div>
@empty
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Belum ada ulasan untuk film ini. Jadilah yang pertama!
    </div>
@endforelse

@endsection
