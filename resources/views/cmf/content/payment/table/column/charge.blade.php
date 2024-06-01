<?php
/** @var \App\Models\Payment $oItem */
?>
<div class="alert alert-sm" role="alert">
    @if(count($oItem->charges) !== 0)
        @foreach($oItem->charges as $charge)
            <span style="font-size: 12px;">$ {{ $charge->amount }}</span>
        @endforeach
    @else
        -
    @endif
</div>
