<?php
/** @var \App\Models\Payment $oItem */
?>
@include('cmf.content.default.table.column.status', [
    'oItem' => $oItem,
])
@if(!is_null($oItem->reservation) && !is_null($oItem->reservation->cancelled_type))
    @include('cmf.components.tooltip.question', [
        'title' => $oItem->reservation->cancelledTypeText,
    ])
@endif
