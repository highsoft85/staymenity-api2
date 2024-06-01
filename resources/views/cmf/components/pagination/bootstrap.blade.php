<ul class="pagination" style="float: right;">
    <li class="page-item">
        @if ($paginator->onFirstPage())
            <button class="btn btn-link" disabled="disabled">
                Previous
            </button>
        @else
            <button class="btn btn-link ajax-link"
                    action="{{ $paginator->previousPageUrl() }}" data-pagination="1"
                    data-pagination-container="{{ $list ?? '.table-component-pagination' }}"
            >
                Previous
            </button>
        @endif
    </li>

    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li class="page-item">
                <button class="btn btn-link" disabled="disabled">{{ $element }}</button>
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
                    <li class="page-item active">
                        <button class="btn btn-link page-link is-current {{-- is-disabled --}}">{{ $page }}</button>
                    </li>
                @else
                    <li class="page-item">
                        <button class="btn btn-link page-link ajax-link" data-pagination="1"
                                data-pagination-container="{{ $list ?? '.table-component-pagination' }}"
                                action="{{ $url }}"
                        >
                            {{ $page }}
                        </button>
                    </li>
                @endif
            @endforeach
        @endif
    @endforeach

    <li class="page-item">
        @if ($paginator->hasMorePages())
            <button class="btn btn-link ajax-link"
                    action="{{ $paginator->nextPageUrl() }}" data-pagination="1"
                    data-pagination-container="{{ $list ?? '.table-component-pagination' }}"
            >
                Next
            </button>
        @else
            <button class="btn btn-link is-black" disabled="disabled">Next</button>
        @endif
    </li>
</ul>
