<?php
/** @var \App\Models\Reservation $oItem */
?>

@if($oItem->fromApp())
    <div class="alert alert-sm alert-info" role="alert">
        <span style="font-size: 12px;">{{ $oItem->start_at->format('m/d/Y') }} ({{ $oItem->reservationTime }})</span>
    </div>
@endif
@if($oItem->fromHostfully())
    <div class="alert alert-sm alert-info" role="alert">
        <span style="font-size: 12px;">{{ $oItem->start_at->format('m/d/Y') }} - {{ $oItem->finish_at->copy()->addDay()->startOfDay()->format('m/d/Y') }}</span>
    </div>
@endif
