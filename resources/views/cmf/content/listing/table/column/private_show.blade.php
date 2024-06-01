<?php
/** @var \App\Models\Listing $oItem */
?>
@if(!is_null($oItem->type) && $oItem->isActive())
    <a class="btn btn-sm text-primary" href="{{ $oItem->getUrl() }}" target="_blank"
       data-tippy-popover
       data-tippy-content="Go to the page"
    >
        <i class="fa fa-link" aria-hidden="true"></i>
    </a>
@endif
@if(!is_null($oItem->hostfully))
    @if(isDeveloperMode())
        <a class="btn btn-sm trigger"
           data-dialog="#custom-edit-modal" data-ajax
           data-action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionDevHostfully', 'id' => $oItem->id]) }}"
           data-ajax-init="{{ $init ?? '' }}"
           data-edit="{{ $oItem->id }}"
           data-model="{{ $model }}"
           data-tippy-popover
           data-tippy-content="Leads"
        >
            <img src="{{ asset('/img/services/hostfully.png') }}" alt="" width="20">
        </a>
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
        <a class="btn btn-sm text-primary" href="{{ $oItem->getUrl() }}" target="_blank"
           data-tippy-popover
           data-tippy-content="Has sync Hostfully"
        >
            <img src="{{ asset('/img/services/hostfully.png') }}" alt="" width="20">
        </a>
    @endif
@endif
