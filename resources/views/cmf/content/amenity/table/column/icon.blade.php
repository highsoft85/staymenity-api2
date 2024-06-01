<?php
/** @var \App\Models\Amenity $oItem */
?>
@if(!is_null($oItem->icon))
    <img src="{{ $oItem->iconSvg }}" alt="" width="15">
{{--    <img src="{{ $oItem->iconPngLight }}" alt="" width="15">--}}
{{--    <img src="{{ $oItem->iconPngDark }}" alt="" width="15">--}}
@endif
