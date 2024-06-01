<?php
/** @var \App\Models\UserIdentity $oItem */
?>
@if($oItem->imagesIdentityFront->count() !== 0)
    <a class="btn btn-sm btn-tiny" href="{{ $oItem->frontImageOriginal }}" target="_blank"
       data-tippy-popover
       data-tippy-content="Front"
    >
        <i class="fa fa-id-card-o" aria-hidden="true"></i>
    </a>
@endif
@if($oItem->imagesIdentityBack->count() !== 0)
    <a class="btn btn-sm btn-tiny text-black" href="{{ $oItem->backImageOriginal }}" target="_blank"
       data-tippy-popover
       data-tippy-content="Back"
    >
        <i class="fa fa-address-card" aria-hidden="true"></i>
    </a>
@endif
@if($oItem->imagesIdentitySelfie->count() !== 0)
    <a class="btn btn-sm btn-tiny text-default" href="{{ $oItem->selfieImageOriginal }}" target="_blank"
       data-tippy-popover
       data-tippy-content="Selfie"
    >
        <i class="fa fa-user-o" aria-hidden="true"></i>
    </a>
@endif
