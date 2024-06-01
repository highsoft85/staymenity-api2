<?php
/** @var \App\Models\Listing $oItem */
?>

<label class="switch switch-pill switch-outline-success-alt switch-sm"
       data-tippy-popover
       data-tippy-content="{{ $oItem->hostfully !== null ? 'Delete sync' : 'Set sync' }}"
>
    <input class="switch-input ajax-checkbox" type="checkbox" name="sync" {{ $oItem->hostfully !== null ? 'checked' : '' }}
    action="{{ routeCmf($model . '.action.item.post', ['id' => $oItem->id, 'name' => 'actionSync']) }}"
           data-list=".admin-table"
           data-list-action="{{ routeCmf($model.'.view.post') }}"
           data-callback="refreshAfterSubmit"
    >
    <span class="switch-slider"></span>
</label>
