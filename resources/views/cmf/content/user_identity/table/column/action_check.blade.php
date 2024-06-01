<?php
/** @var \App\Models\UserIdentity $oItem */
?>
<a class="btn btn-success btn-sm ajax-link {{ $oItem->status === \App\Models\UserIdentity::STATUS_SUCCESS ? 'disabled' : '' }}"
   action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionCheckStatus', 'id' => $oItem->id]) }}"
   data-loading="1"
   data-list=".admin-table"
   data-list-action="{{ routeCmf($model.'.view.post') }}"
   data-form-data=".pagination-form"
   data-callback="refreshAfterSubmit"
   data-tippy-popover
   data-tippy-content="Send to check status from Autohost"
   style="color: #fff;"
>
    <i class="fa fa-share-square-o" aria-hidden="true"></i>
</a>
