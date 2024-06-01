<button class="btn btn-sm float-right ajax-link"
        action="{{ routeCmf($model.'.query', ['all' => true]) }}"
        data-search-container=".breadcrumb + div"
        data-callback="resetSearchFilters, refreshAfterSubmit"
        data-list=".admin-table"
        data-list-action="{{ routeCmf($model.'.view.post') }}"
        style="{{ isset($last) && $last ? 'margin: 5px 25px 5px 5px;' : 'margin: 5px 10px 5px 5px;' }}"
        title="Очистить фильтры"
        data-loading="1"
>
    <i class="fa fa-close"></i>
</button>
