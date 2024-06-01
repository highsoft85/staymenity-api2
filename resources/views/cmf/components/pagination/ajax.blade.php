<nav class="pagination is-right">
    @if ($paginator->onFirstPage())
        <a class="button pagination-previous is-disabled">
            Previous
        </a>
    @else
        <a class="button pagination-previous ajax-link"
           action="{{ $paginator->previousPageUrl() }}" data-pagination="1"
           data-pagination-container=".table-component-pagination"
        >
            Previous
        </a>
    @endif

    @if ($paginator->hasMorePages())
        <a class="button pagination-next ajax-link"
           action="{{ $paginator->nextPageUrl() }}" data-pagination="1"
           data-pagination-container=".table-component-pagination"
        >
            Next
        </a>
    @else
        <a class="button pagination-next is-disabled">Next</a>
    @endif

    <ul class="pagination-list">
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li>
                <a class="pagination-link is-disabled">{{ $element }}</a>
            </li>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <form class="pagination-form">
                        <input type="hidden" name="page" value="{{ $page }}">
                        @if(isset($aSearch) && !empty($aSearch))
                            @foreach($aSearch as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                        @endif
                    </form>
                    <li>
                        <a class="pagination-link is-current {{-- is-disabled --}}">{{ $page }}</a>
                    </li>
                @else
                    <li>
                        <a class="button pagination-link ajax-link" data-pagination="1"
                           data-pagination-container=".table-component-pagination"
                           action="{{ $url }}"
                        >
                            {{ $page }}
                        </a>
                    </li>
                @endif
            @endforeach
        @endif
    @endforeach
    </ul>
</nav>
