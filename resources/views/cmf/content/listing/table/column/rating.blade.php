<?php
/** @var \App\Models\Listing $oItem */
?>
@if(count($oItem->reservationsActive) !== 0)
    <div class="alert alert-sm" role="alert">
        <span style="font-size: 12px;">{{ number_format($oItem->ratingsToAverageByReview(), 2) ?? 0 }} ({{ $oItem->ratingsToCountByReview() }})</span>
    </div>
@else
    <div class="alert alert-sm" role="alert">
        <span style="font-size: 12px;">0 (0)</span>
    </div>
@endif
