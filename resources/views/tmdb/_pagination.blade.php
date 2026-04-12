{{-- Partial: Pagination TMDB --}}
@if($totalPages > 1)
<nav aria-label="Pagination" class="mt-4">
    <ul class="pagination justify-content-center flex-wrap">
        {{-- Previous --}}
        <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $prevUrl }}">&laquo; Prev</a>
        </li>

        {{-- Page numbers --}}
        @php
            $start = max(1, $currentPage - 3);
            $end = min($totalPages, $currentPage + 3);
        @endphp

        @if($start > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $pageUrl(1) }}">1</a>
            </li>
            @if($start > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
        @endif

        @for($i = $start; $i <= $end; $i++)
            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="{{ $pageUrl($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if($end < $totalPages)
            @if($end < $totalPages - 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
            <li class="page-item">
                <a class="page-link" href="{{ $pageUrl($totalPages) }}">{{ $totalPages }}</a>
            </li>
        @endif

        {{-- Next --}}
        <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $nextUrl }}">&raquo; Next</a>
        </li>
    </ul>
</nav>
@endif
