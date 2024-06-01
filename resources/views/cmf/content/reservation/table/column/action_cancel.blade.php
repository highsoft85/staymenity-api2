<?php
/** @var \App\Models\Reservation $oItem */
?>
{{--<a class="btn btn-danger btn-sm ajax-link {{ $oItem->isCancelled() || $oItem->isDeclined() || $oItem->isBeginning() || $oItem->isPassed() || $oItem->fromHostfully() ? 'disabled' : '' }}"--}}
{{--   action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionCancelReservation', 'id' => $oItem->id]) }}"--}}
{{--   data-loading="1"--}}
{{--   data-list=".admin-table"--}}
{{--   data-list-action="{{ routeCmf($model.'.view.post') }}"--}}
{{--   data-form-data=".pagination-form"--}}
{{--   data-callback="refreshAfterSubmit"--}}
{{--   data-tippy-popover--}}
{{--   data-tippy-content="Cancel Reservation and Refund Payment"--}}
{{-->--}}
{{--    <i class="icon-ban" style="color: #fff;"></i>--}}
{{--</a>--}}

<a class="btn btn-danger btn-sm is-small trigger {{ $oItem->isCancelled() || $oItem->isDeclined() || $oItem->isBeginning() || $oItem->isPassed() || $oItem->fromHostfully() ? 'disabled' : '' }}"
   data-dialog="#pages-dialogs-confirm"
   data-confirm
   data-text='Are you sure you want to cancel?'
   data-action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionCancelReservation', 'id' => $oItem->id]) }}"
   data-list-action="{{ routeCmf($model.'.view.post') }}"
   data-loading="1"
   data-list=".admin-table"
   data-form-data=".pagination-form"
   data-callback="refreshAfterSubmit"
   data-tippy-popover
   data-tippy-content="Cancel Reservation and Refund Payment"
>
    <i class="icon-ban" style="color: #fff;"></i>
</a>
