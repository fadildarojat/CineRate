{{-- Partial: Card film TMDB --}}
<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ route('tmdb.detail', $movie['id']) }}" class="text-decoration-none d-block w-100 h-100">
        <div class="card card-film h-100">
            @if(!empty($movie['poster_path']))
                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                     class="card-img-top" alt="{{ $movie['title'] }}" loading="lazy">
            @else
                <div class="card-img-top no-poster" style="height: 350px;">
                    <i class="bi bi-film"></i>
                </div>
            @endif

            <div class="card-body">
                <h5 class="card-title fw-bold">{{ $movie['title'] }}</h5>

                <p class="text-muted mb-2" style="font-size: 0.9rem;">
                    {{ !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '-' }}
                </p>

                <div class="mt-2 d-flex align-items-center gap-2">
                    <span class="imdb-rating-badge">
                        <i class="bi bi-star-fill"></i>
                        {{ number_format($movie['vote_average'] ?? 0, 1) }}
                    </span>
                    <span class="text-muted" style="font-size: 0.8rem;">
                        ({{ number_format($movie['vote_count'] ?? 0) }})
                    </span>
                </div>
            </div>
        </div>
    </a>
</div>
