@if($oItems instanceof \Illuminate\Pagination\LengthAwarePaginator && ($oItems->currentPage() === $oItems->lastPage() || $oItems->hasMorePages()))
    @if(isset($aSearch))
        {{ $oItems->appends($aSearch)->links('cmf.components.pagination.bootstrap') }}
    @else
        {{ $oItems->links('cmf.components.pagination.bootstrap') }}
    @endif
@endif
