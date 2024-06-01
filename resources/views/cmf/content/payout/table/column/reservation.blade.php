<?php
/** @var \App\Models\Payout $oItem */
?>
<div class="alert alert-sm alert-info" role="alert">
    @if(!is_null($oItem->reservation))
        <span style="font-size: 12px;">#{{ $oItem->reservation->id }}: {{ $oItem->reservation->start_at->format('m/d/Y') }} ({{ $oItem->reservation->reservationTime }})</span>
    @else
        <span style="font-size: 12px;">-</span>
    @endif
</div>
