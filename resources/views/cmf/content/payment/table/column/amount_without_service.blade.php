<?php
/** @var \App\Models\Payment $oItem */
?>
<div class="alert alert-sm" role="alert">
    <span style="font-size: 12px;">$ {{ $oItem->amountWithoutService }}</span>
    @if($oItem->status === \App\Models\Payment::STATUS_ACTIVE)
        <i class="fa fa-long-arrow-right text-success ml-2"></i>
    @else
        <i class="fa fa-long-arrow-right text-default ml-2"></i>
    @endif
</div>
