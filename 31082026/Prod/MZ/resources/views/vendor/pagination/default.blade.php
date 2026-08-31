<?php
$count = $paginator->count();
$total = $paginator->total();
$currentPage = $paginator->currentPage();
$lastPage = $paginator->lastPage();
?>
<div class="paginator float-right">
@if ($paginator->hasPages())
    <ul class="pagination">
        <li class="page-item active"><a class="page-link"><b>Show {{  $count }} Items out of {{  $total }}</b></a></li>
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled page-item"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link ajax_page" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled page-item"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active page-item"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link ajax_page" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link ajax_page" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif
        <li class="page-item active"><a class="page-link"><b>Page {{$currentPage}} of {{$lastPage}}</b></a></li>
    </ul>
@endif
</div>
