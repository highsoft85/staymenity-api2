<?php
/** @var \App\Models\Reservation $oItem */
?>
@if($oItem->sync_hostfully === 1)
    <img src="{{ asset('/img/services/hostfully.png') }}" alt="" width="20"
         data-tippy-popover
         data-tippy-content="Has sync Hostfully"
    >
@endif
