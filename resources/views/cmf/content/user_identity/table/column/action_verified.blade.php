<?php
/** @var \App\Models\UserIdentity $oItem */
?>
<a class="btn btn-dark btn-sm ajax-link {{ $oItem->status === \App\Models\UserIdentity::STATUS_SUCCESS ? 'disabled' : '' }}"
   action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionForceVerified', 'id' => $oItem->id]) }}"
   data-loading="1"
   data-list=".admin-table"
   data-list-action="{{ routeCmf($model.'.view.post') }}"
   data-form-data=".pagination-form"
   data-callback="refreshAfterSubmit"
   data-tippy-popover
   data-tippy-content="Set verified Force, without Autohost"
   style="color: #fff;"
>
    <i class="fa fa-check-circle-o" aria-hidden="true"></i>
</a>
