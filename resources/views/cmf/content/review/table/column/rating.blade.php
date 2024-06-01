<?php
/** @var \App\Models\Review $oItem */
?>
@if(!is_null($oItem->rating))
    {{ $oItem->rating }}
@else
    -
@endif
