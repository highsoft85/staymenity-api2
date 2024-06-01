<?php
/** @var \App\Models\Amenity $oItem */
?>
<div class="alert alert-default alert-sm text-center" role="alert">
    <span style="font-size: 12px;">{{ $oItem->listings()->active()->count() }} / {{ $oItem->listings()->count() }}</span>
</div>
