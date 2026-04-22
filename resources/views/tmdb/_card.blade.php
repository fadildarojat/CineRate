{{-- Partial: Card film TMDB --}}
<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card card-film h-100 position-relative">
        <a href="{{ route('tmdb.detail', $movie['id']) }}" class="text-decoration-none d-block">
            @if(!empty($movie['poster_path']))
                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                     class="card-img-top" alt="{{ $movie['title'] }}" loading="lazy">
            @else
                <div class="card-img-top no-poster" style="height: 350px;">
                    <i class="bi bi-film"></i>
                </div>
            @endif
        </a>

        {{-- Watch Overlay on Hover --}}
        <a href="{{ route('streaming.watch', $movie['id']) }}" class="card-watch-overlay" title="Tonton {{ $movie['title'] }}">
            <i class="bi bi-play-circle-fill"></i>
        </a>

        <div class="card-body">
            <a href="{{ route('tmdb.detail', $movie['id']) }}" class="text-decoration-none">
                <h5 class="card-title fw-bold">{{ $movie['title'] }}</h5>
            </a>

            <p class="text-muted mb-2" style="font-size: 0.9rem;">
                {{ !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '-' }}
            </p>

            <div class="mt-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="imdb-rating-badge">
                        <i class="bi bi-star-fill"></i>
                        {{ number_format($movie['vote_average'] ?? 0, 1) }}
                    </span>
                    <span class="text-muted" style="font-size: 0.8rem;">
                        ({{ number_format($movie['vote_count'] ?? 0) }})
                    </span>
                </div>
                <a href="{{ route('streaming.watch', $movie['id']) }}" class="btn-watch-small" title="Tonton">
                    <i class="bi bi-play-fill"></i>
                </a>
            </div>
        </div>
    </div>
</div>
