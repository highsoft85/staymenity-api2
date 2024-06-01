@extends('cmf.layouts.cmf')

@php
    $hasSearch = false;
@endphp
@foreach($fields as $key => $field)
    @if(!is_null(Request()->{$key}))
        @php
            $hasSearch = true;
        @endphp
    @endif
@endforeach

@section('content.title')
    @include('cmf.content.default.table.title')
@endsection
@section('breadcrumb-search')
    @component('cmf.content.default.table.search.bar', ['hasSearch' => !empty($indexComponent['search']) && $indexComponent['search']])
        <form id="search-bar-form" action="{{ routeCmf($model.'.query') }}" class="row ajax-form"
              data-loading="1"
              data-view=".admin-table"
              data-callback="updateView"
              data-delay="1"
{{--              data-fast-search=""--}}
              data-outer-loading='.admin-table'
              data-ajax-init="tooltip"
        >
            @if(isset($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_SEARCH_FIELDS]) && !is_null($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_SEARCH_FIELDS]))
                @foreach($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_SEARCH_FIELDS] as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
            @endif
            @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.table.search.data'))
                @include('cmf.content.' . $model . '.table.search.data')
            @else
                @include('cmf.content.default.table.search.data')
            @endif
        </form>
    @endcomponent
@endsection
@section('breadcrumb-right')
    @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.table.create'))
        @include('cmf.content.' . $model . '.table.create')
    @else
        @if(!empty($indexComponent['create']) && $indexComponent['create'])
            @include('cmf.content.default.table.create')
        @endif
    @endif
    @if(!empty($indexComponent['export']) && $indexComponent['export'])
        @include('cmf.content.default.table.components.export')
    @endif
    <a class="btn btn-primary ajax-link --search-bar-submit {{ !empty($indexComponent['search']) && $indexComponent['search'] ? '' : 'hidden' }}" href="#"
       data-form="#search-bar-form"
       style="margin-right: 10px; padding-left: 1rem;"
    >
        Search
    </a>
    <a class="btn btn-default text-black --search-bar-reset hidden" style="margin-right: 10px; padding-left: 1rem;cursor: pointer;">
        Clear
    </a>
    <a class="btn btn-default text-black {{ !empty($indexComponent['search']) && $indexComponent['search'] ? '' : 'hidden' }}"
       data-toggle="collapse" href="#search-bar" aria-expanded="true" aria-controls="search-bar"
       style="margin-right: 10px; width: 47px;padding-left: 1rem;"
    >
        <i class="fa fa-search" aria-hidden="true"></i>
    </a>
    @if(isset($oItem) && !empty($oItem))
        <div class="hidden">
            @include('cmf.content.default.table.column.edit', [
                'class' => '--open-dialog-important'
            ])
        </div>
    @endif
@endsection

@section('content')
    @component('cmf.components.table.index')
        <div class="admin-table table-component-pagination is-{{ $model }}-admin-table">
            @include('cmf.content.default.table.table_before', [
                'oItems' => $oItems
            ])
        </div>
    @endcomponent
@endsection
