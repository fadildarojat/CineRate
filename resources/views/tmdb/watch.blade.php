{{-- ============================================
    HALAMAN STREAMING FILM
    Menampilkan embedded video player
    Multi-source dengan server switching
    ============================================ --}}

@extends('layouts.app')

@section('title', 'Tonton ' . ($movie['title'] ?? 'Film') . ' - CineRate')

@section('content')

{{-- Tombol Navigasi --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <a href="{{ route('tmdb.detail', $movie['id']) }}" class="btn btn-outline-imdb">
        <i class="bi bi-arrow-left"></i> Detail Film
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('tmdb.detail', $movie['id']) }}#ulasan" class="btn btn-outline-imdb">
            <i class="bi bi-chat-dots"></i> Ulasan
        </a>
    </div>
</div>

{{-- Judul Film --}}
<div class="mb-3">
    <h1 style="font-size: clamp(1.3rem, 4vw, 2rem); font-weight: 700; color: #fff; margin-bottom: 0.25rem;">
        <i class="bi bi-play-circle-fill" style="color: var(--imdb-yellow);"></i>
        {{ $movie['title'] }}
    </h1>
    <p style="color: var(--imdb-text-muted); margin-bottom: 0; font-size: 0.9rem;">
        {{ !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '-' }}
        @if(!empty($movie['genres']))
            &nbsp;|&nbsp;
            @foreach($movie['genres'] as $genre)
                {{ $genre['name'] }}@if(!$loop->last), @endif
            @endforeach
        @endif
        @if(!empty($movie['runtime']))
            &nbsp;|&nbsp; {{ floor($movie['runtime'] / 60) }}j {{ $movie['runtime'] % 60 }}m
        @endif
    </p>
</div>

{{-- ============================================ --}}
{{-- SERVER SWITCHING BUTTONS                    --}}
{{-- ============================================ --}}
<div class="server-switcher mb-3">
    <span style="color: var(--imdb-text-muted); font-size: 0.85rem; margin-right: 0.5rem;">
        <i class="bi bi-hdd-stack"></i> Pilih Server:
    </span>
    @foreach($sources as $source)
        <a href="{{ route('streaming.watch', ['id' => $movie['id'], 'source' => $source['key']]) }}"
           class="btn btn-server {{ $source['active'] ? 'active' : '' }}">
            <i class="bi bi-{{ $source['active'] ? 'broadcast-pin' : 'play-circle' }}"></i>
            {{ $source['name'] }}
        </a>
    @endforeach
</div>

{{-- ============================================ --}}
{{-- VIDEO PLAYER (Embedded)                     --}}
{{-- ============================================ --}}
<div class="video-player-container mb-4">
    <div class="video-player-wrapper">
        <iframe
            src="{{ $embedUrl }}"
            class="video-player"
            frameborder="0"
            allowfullscreen
            allow="autoplay; encrypted-media; picture-in-picture"
            referrerpolicy="origin"
            loading="lazy"
        ></iframe>
    </div>
</div>

{{-- Pesan Info --}}
<div class="alert alert-info mb-4" style="font-size: 0.85rem;">
    <i class="bi bi-info-circle"></i>
    <strong>Tips:</strong> Jika video tidak muncul atau error, coba ganti server di atas.
    Tekan tombol <i class="bi bi-fullscreen"></i> di player untuk fullscreen.
</div>

{{-- ============================================ --}}
{{-- INFO FILM DI BAWAH PLAYER                   --}}
{{-- ============================================ --}}
<div class="row mt-4">
    {{-- Sinopsis --}}
    <div class="col-lg-8 mb-4">
        <div class="streaming-info-box">
            <h4 class="fw-bold mb-3" style="color: var(--imdb-yellow);">
                <i class="bi bi-info-circle"></i> Sinopsis
            </h4>
            <p style="line-height: 1.8; color: var(--imdb-text);">
                {{ $movie['overview'] ?: 'Sinopsis tidak tersedia.' }}
            </p>

            @if(!empty($movie['credits']['cast']))
                <h5 class="fw-bold mt-4 mb-3" style="color: var(--imdb-yellow);">
                    <i class="bi bi-people"></i> Pemeran Utama
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(array_slice($movie['credits']['cast'], 0, 8) as $cast)
                        <span class="badge badge-genre">
                            {{ $cast['name'] }}
                            @if(!empty($cast['character']))
                                <small style="color: var(--imdb-text-muted);"> sebagai {{ $cast['character'] }}</small>
                            @endif
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="col-lg-4 mb-4">
        {{-- Rating TMDB --}}
        <div class="streaming-info-box mb-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="imdb-rating-badge" style="font-size: 1.1rem;">
                    <i class="bi bi-star-fill"></i>
                    {{ number_format($movie['vote_average'] ?? 0, 1) }}
                </span>
                <span style="color: var(--imdb-text-muted); font-size: 0.85rem;">
                    /10 &middot; {{ number_format($movie['vote_count'] ?? 0) }} vote
                </span>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="streaming-info-box mb-3">
            <h5 class="fw-bold mb-3" style="color: var(--imdb-yellow); font-size: 1rem;">
                <i class="bi bi-lightning"></i> Aksi Cepat
            </h5>
            <div class="d-grid gap-2">
                <a href="{{ route('tmdb.detail', $movie['id']) }}" class="btn btn-outline-imdb btn-sm">
                    <i class="bi bi-star"></i> Beri Rating & Ulasan
                </a>
            </div>
        </div>

        {{-- Film Serupa --}}
        @if(!empty($movie['similar']['results']))
            <div class="streaming-info-box">
                <h5 class="fw-bold mb-3" style="color: var(--imdb-yellow); font-size: 1rem;">
                    <i class="bi bi-collection-play"></i> Film Serupa
                </h5>
                @foreach(array_slice($movie['similar']['results'], 0, 4) as $similar)
                    <a href="{{ route('streaming.watch', $similar['id']) }}"
                       class="d-flex align-items-center gap-2 mb-2 text-decoration-none similar-item">
                        @if(!empty($similar['poster_path']))
                            <img src="https://image.tmdb.org/t/p/w92{{ $similar['poster_path'] }}"
                                 alt="{{ $similar['title'] }}"
                                 style="width: 45px; height: 65px; object-fit: cover; border-radius: 4px;">
                        @else
                            <div style="width: 45px; height: 65px; background: var(--imdb-dark-5); border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-film" style="color: var(--imdb-text-muted);"></i>
                            </div>
                        @endif
                        <div>
                            <div style="color: #fff; font-size: 0.85rem; font-weight: 500;">
                                {{ Str::limit($similar['title'], 25) }}
                            </div>
                            <div style="color: var(--imdb-text-muted); font-size: 0.75rem;">
                                <i class="bi bi-star-fill" style="color: var(--imdb-yellow); font-size: 0.7rem;"></i>
                                {{ number_format($similar['vote_average'] ?? 0, 1) }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<style>
    /* ---- VIDEO PLAYER STYLES ---- */
    .video-player-container {
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.6);
        border: 1px solid var(--imdb-dark-4);
    }

    .video-player-wrapper {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        height: 0;
        overflow: hidden;
    }

    .video-player {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    /* ---- SERVER SWITCHER ---- */
    .server-switcher {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .btn-server {
        background: var(--imdb-dark-3);
        border: 1px solid var(--imdb-dark-5);
        color: var(--imdb-text);
        padding: 0.4rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-server:hover {
        background: var(--imdb-dark-4);
        border-color: var(--imdb-yellow);
        color: var(--imdb-yellow);
    }

    .btn-server.active {
        background: linear-gradient(135deg, #e50914, #b20710);
        border-color: #e50914;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 2px 10px rgba(229, 9, 20, 0.3);
    }

    /* ---- STREAMING INFO BOX ---- */
    .streaming-info-box {
        background: var(--imdb-dark-3);
        border: 1px solid var(--imdb-dark-4);
        border-radius: 8px;
        padding: clamp(1rem, 3vw, 1.5rem);
    }

    /* ---- SIMILAR ITEM HOVER ---- */
    .similar-item {
        padding: 0.5rem;
        border-radius: 6px;
        transition: background-color 0.2s ease;
    }

    .similar-item:hover {
        background-color: var(--imdb-dark-4);
    }

    /* ---- RESPONSIVE ADJUSTMENTS ---- */
    @media (max-width: 768px) {
        .video-player-container {
            border-radius: 8px;
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
    }
</style>
@endsection
