@php
    $hide_title = true;
    $delete_title = '';
    $delete_title_name = 'name';
@endphp

@if(count($oItems) !== 0)
    @if(View::exists('cmf.content.' . $model . '.table.table'))
        @include('cmf.content.' . $model . '.table.table', [
            'oItems' => $oItems
        ])
    @else
        @include('cmf.content.default.table.table', [
            'oItems' => $oItems
        ])
    @endif
@else
    <div class="text-muted page-desc">Empty</div>
@endif
