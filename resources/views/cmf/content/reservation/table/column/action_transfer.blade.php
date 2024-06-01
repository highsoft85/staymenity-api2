<?php
/** @var \App\Models\Reservation $oItem */
?>
<a class="btn btn-info btn-sm ajax-link {{ $oItem->isActive() && is_null($oItem->transfer) && ($oItem->isBeginning() || $oItem->isPassed()) && $oItem->fromApp() ? '' : 'disabled' }}"
   action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionTransferReservation', 'id' => $oItem->id]) }}"
   data-loading="1"
   data-list=".admin-table"
   data-list-action="{{ routeCmf($model.'.view.post') }}"
   data-form-data=".pagination-form"
   data-callback="refreshAfterSubmit"
   data-tippy-popover
   data-tippy-content="Transfer for Reservation"
>
    <i class="fa fa-exchange" aria-hidden="true" style="color: #fff;"></i>
</a>
