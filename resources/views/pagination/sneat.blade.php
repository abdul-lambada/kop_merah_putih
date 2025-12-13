@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination">
            {{-- First Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item first disabled" aria-disabled="true">
                    <span class="page-link">
                        <i class="tf-icon bx bx-chevrons-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item first">
                    <a class="page-link" href="{{ $paginator->url(1) }}" rel="prev">
                        <i class="tf-icon bx bx-chevrons-left"></i>
                    </a>
                </li>
            @endif

            {{-- Previous Page Link --}}
            @if ($paginator->currentPage() > 1)
                <li class="page-item prev">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="tf-icon bx bx-chevron-left"></i>
                    </a>
                </li>
            @else
                <li class="page-item prev disabled" aria-disabled="true">
                    <span class="page-link">
                        <i class="tf-icon bx bx-chevron-left"></i>
                    </span>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item next">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <i class="tf-icon bx bx-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item next disabled" aria-disabled="true">
                    <span class="page-link">
                        <i class="tf-icon bx bx-chevron-right"></i>
                    </span>
                </li>
            @endif

            {{-- Last Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item last">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" rel="next">
                        <i class="tf-icon bx bx-chevrons-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item last disabled" aria-disabled="true">
                    <span class="page-link">
                        <i class="tf-icon bx bx-chevrons-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
