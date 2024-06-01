<?php
/** @var \App\Models\Listing $oItem */
?>
<div class="alert alert-default alert-sm text-center" role="alert">
    @if(count($oItem->reservationsActive) !== 0)
        <span style="font-size: 12px;">
            {{ $oItem->reservationsActive()->future()->count() }}
            /
            {{ $oItem->reservationsActive()->beginning()->count() }}
            /
            {{ $oItem->reservationsActive()->passed()->count() }}
            /
            {{ $oItem->reservationsActive()->cancelledOrDeclinedOrNotActive()->count() }}
            /
            {{ $oItem->reservationsActive()->count() }}
        </span>
    @else
        <span style="font-size: 12px;">
            0
            /
            0
            /
            0
            /
            0
            /
            0
        </span>
    @endif
</div>
