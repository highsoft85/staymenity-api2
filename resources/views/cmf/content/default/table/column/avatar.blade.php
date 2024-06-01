<div class="avatar">
    <img class="img-avatar" src="{{ $oItem->image_square }}" width="30" data-fancybox="gallery-post-{{ $model }}" href="{{ $oItem->image }}" style="cursor: pointer">
    <span
        class="avatar-status {{ $oItem->isActive() ? 'badge-success' : 'badge-danger' }}"
        data-tippy-popover data-tippy-content="{{ method_exists($oItem, 'statuses') ? $oItem->status_text : '' }}"
    ></span>
</div>



{{--@if(isset($path))--}}
{{--    @if(ImagePath::checkMain($model, $path, $oItem))--}}
{{--        <img src="{{ ImagePath::main($model, $path, $oItem) }}" width="30"--}}
{{--             data-fancybox="gallery-post-{{ $model }}"--}}
{{--             href="{{ ImagePath::main($model, 'original', $oItem) }}"--}}
{{--             style="cursor: pointer"--}}
{{--        >--}}
{{--    @else--}}
{{--        <img src="{{ ImagePath::main($model, $path, $oItem) }}" width="30">--}}
{{--    @endif--}}
{{--@else--}}
{{--    <img src="{{ ImagePath::main($model, 'original', $oItem) }}" width="30"--}}
{{--         data-fancybox="gallery-post-{{ $model }}"--}}
{{--         href="{{ ImagePath::main($model, 'original', $oItem) }}"--}}
{{--         style="cursor: pointer"--}}
{{--    >--}}
{{--@endif--}}
