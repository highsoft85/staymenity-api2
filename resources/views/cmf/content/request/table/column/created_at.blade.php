<?php
/** @var \App\Models\Request $oItem */
?>
<div class="alert alert-sm alert-default" role="alert">
    @if(!is_null($oItem->created_at))
        <span style="font-size: 12px;">{{ $oItem->created_at->format('m/d/Y H:i:s') }}</span>
    @else
        <span style="font-size: 12px;">-</span>
    @endif
</div>
