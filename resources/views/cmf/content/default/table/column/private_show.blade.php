@if($oItem->isActive())
    <a class="btn btn-sm text-primary" href="{{ $oItem->getUrl() }}" target="_blank"
       data-tippy-popover
       data-tippy-content="Go to the page"
    >
        <i class="fa fa-link" aria-hidden="true"></i>
    </a>
{{--@else--}}
{{--    <a class="btn btn-sm text-primary" href="{{ $oItem->getUrl() }}" target="_blank"--}}
{{--       data-tippy-popover--}}
{{--       data-tippy-content="Перейти на страницу"--}}
{{--    >--}}
{{--        <i class="fa fa-link" aria-hidden="true"></i>--}}
{{--    </a>--}}
{{--    <a class="btn btn-sm text-black" href="{{ $oItem->getUrl() . '?hash=' . \Illuminate\Support\Str::random(32) }}" target="_blank"--}}
{{--       data-tippy-popover--}}
{{--       data-tippy-content="Закрытый просмотр"--}}
{{--    >--}}
{{--        <i class="fa fa-external-link" aria-hidden="true"></i>--}}
{{--    </a>--}}
@endif
