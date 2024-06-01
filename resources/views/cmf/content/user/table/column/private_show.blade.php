<?php
/** @var \App\Models\User $oItem */
?>
@if($oItem->isActive())
    @if($oItem->hasAllRoles([\App\Models\User::ROLE_HOST]))
        <a class="btn btn-sm text-primary" href="{{ $oItem->getHostUrl() }}" target="_blank"
           data-tippy-popover
           data-tippy-content="Go to the Host page"
        >
            <i class="fa fa-link" aria-hidden="true"></i>
        </a>
    @endif
    @if($oItem->hasAllRoles([\App\Models\User::ROLE_GUEST]))
        <a class="btn btn-sm text-warning" href="{{ $oItem->getGuestUrl() }}" target="_blank"
           data-tippy-popover
           data-tippy-content="Go to the Guest page"
        >
            <i class="fa fa-link" aria-hidden="true"></i>
        </a>
    @endif
@endif
@if(!is_null($oItem->details) && !is_null($oItem->details->hostfully_agency_uid))
    @if(isDeveloperMode())
        <a class="btn btn-sm trigger"
           data-dialog="#custom-edit-modal" data-ajax
           data-action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionDevHostfullyWebhooks', 'id' => $oItem->id]) }}"
           data-ajax-init="{{ $init ?? '' }}"
           data-edit="{{ $oItem->id }}"
           data-model="{{ $model }}"
           data-tippy-popover
           data-tippy-content="Webhooks"
        >
            <img src="{{ asset('/img/services/hostfully.png') }}" alt="" width="20">
        </a>
    @else
        <a class="btn btn-sm text-primary" href="#" target="_blank"
           data-tippy-popover
           data-tippy-content="Has sync Hostfully"
        >
            <img src="{{ asset('/img/services/hostfully.png') }}" alt="" width="20">
        </a>
    @endif
@endif
