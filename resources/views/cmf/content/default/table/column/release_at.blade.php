<label class="switch switch-pill switch-outline-primary-alt switch-sm"
       data-tippy-popover
       data-tippy-content="Опубликовать"
>
    <input class="switch-input ajax-checkbox" type="checkbox" name="release" {{ $oItem->release_at !== null ? 'checked' : '' }}
           action="{{ routeCmf($model . '.action.item.post', ['id' => $oItem->id, 'name' => 'actionRelease']) }}"
           data-list=".admin-table"
           data-list-action="{{ routeCmf($model.'.view.post') }}"
           data-callback="refreshAfterSubmit"
    >
    <span class="switch-slider"></span>
</label>
