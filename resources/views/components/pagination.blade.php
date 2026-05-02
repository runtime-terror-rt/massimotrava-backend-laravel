{{-- Custom pagination to match the dashboard design --}}
@if ($paginator->hasPages())
  {{-- Previous --}}
  @if ($paginator->onFirstPage())
    <button class="page-btn" disabled>
      <i class="fa-solid fa-chevron-left" style="font-size:10px"></i>
    </button>
  @else
    <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">
      <i class="fa-solid fa-chevron-left" style="font-size:10px"></i>
    </a>
  @endif

  {{-- Page Numbers --}}
  @foreach ($elements as $element)
    @if (is_string($element))
      <button class="page-btn" disabled style="opacity:.4">…</button>
    @endif
    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <button class="page-btn active">{{ $page }}</button>
        @else
          <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
        @endif
      @endforeach
    @endif
  @endforeach

  {{-- Next --}}
  @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">
      <i class="fa-solid fa-chevron-right" style="font-size:10px"></i>
    </a>
  @else
    <button class="page-btn" disabled>
      <i class="fa-solid fa-chevron-right" style="font-size:10px"></i>
    </button>
  @endif
@endif
