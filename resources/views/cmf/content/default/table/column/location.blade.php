@if(!is_null($oItem->location))
    <span class="badge badge-success">Установлено</span>
@else
    <span class="badge badge-danger">Не установлено</span>
@endif
{{--@if(!is_null($oItem->location))--}}
{{--    <a class="btn btn-sm text-success" role="button"--}}
{{--       data-tippy-popover--}}
{{--       data-tippy-content="Установлено"--}}
{{--    >--}}
{{--        <i class="fa fa-map-marker" aria-hidden="true"></i>--}}
{{--    </a>--}}
{{--@else--}}
{{--    <a class="btn btn-sm text-danger" role="button"--}}
{{--       data-tippy-popover--}}
{{--       data-tippy-content="Не установлено"--}}
{{--    >--}}
{{--        <i class="fa fa-map-marker" aria-hidden="true"></i>--}}
{{--    </a>--}}
{{--@endif--}}
