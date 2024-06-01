<a class="btn {{ count($oItem->userActionsActive) !== 0 ? 'btn-secondary' : ' disabled' }} btn-sm trigger"
   data-dialog="#custom-edit-modal"
   data-ajax
   data-action="{{ routeCmf($model . '.action.item.post', ['id' => $oItem->id, 'name' => 'actionUserActionModal']) }}"
   data-model="{{ $model }}"
>
    <i class="icon-people" style="color: #2a2c36"></i>
</a>
