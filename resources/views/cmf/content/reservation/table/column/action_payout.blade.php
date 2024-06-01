<?php
/** @var \App\Models\Reservation $oItem */
?>
<a class="btn btn-info btn-sm ajax-link {{ $oItem->isActive() && !is_null($oItem->transfer) && $oItem->isPassed() && is_null($oItem->payout) && $oItem->fromApp() ? '' : 'disabled' }}"
   action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionPayoutReservation', 'id' => $oItem->id]) }}"
   data-loading="1"
   data-list=".admin-table"
   data-list-action="{{ routeCmf($model.'.view.post') }}"
   data-form-data=".pagination-form"
   data-callback="refreshAfterSubmit"
   data-tippy-popover
   data-tippy-content="Payout for Reservation"
>
    <i class="fa fa-reply" aria-hidden="true" style="color: #fff;"></i>
</a>
