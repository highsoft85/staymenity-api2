<?php
/** @var \App\Models\User $oItem */
?>

@if($oItem->hasPayoutConnect())
    <a class="btn btn-sm btn-tiny text-success" role="button"
       data-tippy-popover
       data-tippy-content="Has payout connect"
    >
        <i class="fa fa-reply" aria-hidden="true"></i>
    </a>
@endif
@if($oItem->identity_verified_at !== null)
    <a class="btn btn-sm btn-tiny text-success" role="button"
       data-tippy-popover
       data-tippy-content="Has success identity verification"
    >
        <i class="fa fa-check-circle-o" aria-hidden="true"></i>
    </a>
@endif
@if(!is_null($oItem->hostfully))
    <a class="btn btn-sm btn-tiny text-success" role="button"
       data-tippy-popover
       data-tippy-content="From Hostfully"
    >
        <img src="{{ asset('/img/services/hostfully.png') }}" alt="" width="20">
    </a>
@endif
