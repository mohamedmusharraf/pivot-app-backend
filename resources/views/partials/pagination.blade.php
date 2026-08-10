@if ($paginator->hasPages())
<div style="display: flex; gap: 0.375rem; align-items: center;">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <button class="btn btn-secondary btn-sm" disabled>Previous</button>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-secondary btn-sm">Previous</a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)
    {{-- Array Of Links --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <span class="btn btn-primary btn-sm">{{ $page }}</span>
    @else
    <a href="{{ $url }}" class="btn btn-secondary btn-sm">{{ $page }}</a>
    @endif
    @endforeach
    @endif

    {{-- "Three Dots" Separator --}}
    @if (is_string($element))
    <span class="btn btn-secondary btn-sm" disabled>{{ $element }}</span>
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-secondary btn-sm">Next</a>
    @else
    <button class="btn btn-secondary btn-sm" disabled>Next</button>
    @endif
</div>
@endif