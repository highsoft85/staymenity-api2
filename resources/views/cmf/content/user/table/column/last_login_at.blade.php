<?php
/** @var \App\Models\User $oItem */
?>
<div class="alert alert-sm alert-default" role="alert">
    <span style="font-size: 12px;">{{ !is_null($oItem->last_login_at) ? $oItem->last_login_at->format('m/d/Y H:i:s') : '-' }}</span>
</div>
