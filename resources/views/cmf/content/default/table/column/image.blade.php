<?php
$model = isset($model) ? $model : $model;
?>
<img src="{{ $oItem->image_square }}" width="30"
     data-fancybox="gallery-post-{{ $model }}"
     href="{{ $oItem->image_original }}"
     style="cursor: pointer"
>
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
