@if ($paginator->hasPages())
    <nav class="my-custom-pagination">
        {{-- Tombol Previous --}}
        @if ($paginator->onFirstPage())
            <span class="disabled">< </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"><</a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">></a>
        @else
            <span class="disabled">></span>
        @endif
    </nav>
@endif
