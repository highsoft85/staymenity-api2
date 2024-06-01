<label class="switch switch-pill switch-outline-danger-alt switch-sm"
       data-tippy-popover
       data-tippy-content="{{ $oItem->banned_at !== null ? 'Set active' : 'Set ban' }}"
>
    <input class="switch-input ajax-checkbox" type="checkbox" name="banned" {{ $oItem->banned_at !== null ? 'checked' : '' }}
    action="{{ routeCmf($model . '.action.item.post', ['id' => $oItem->id, 'name' => 'actionBanned']) }}"
           data-list=".admin-table"
           data-list-action="{{ routeCmf($model.'.view.post') }}"
           data-callback="refreshAfterSubmit"
    >
    <span class="switch-slider"></span>
</label>
