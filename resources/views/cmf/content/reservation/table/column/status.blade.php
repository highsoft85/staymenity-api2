<?php
/** @var \App\Models\Reservation $oItem */
?>
@include('cmf.content.default.table.column.status', [
    'oItem' => $oItem,
])
@if(!is_null($oItem->cancelled_type))
    @include('cmf.components.tooltip.question', [
        'title' => $oItem->cancelledTypeText,
    ])
@endif
