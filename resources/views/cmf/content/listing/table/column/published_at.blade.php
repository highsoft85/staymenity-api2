<?php
/** @var \App\Models\Listing $oItem */
?>
<div class="alert alert-sm alert-default" role="alert">
    <span style="font-size: 12px;">{{ !is_null($oItem->published_at) ? $oItem->published_at->format('m/d/Y H:i:s') : '-' }}</span>
</div>
