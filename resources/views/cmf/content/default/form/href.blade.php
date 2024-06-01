<a class="btn btn-default btn-sm trigger" data-dialog="#custom-edit-modal" data-ajax
   data-action="{{ routeCmf($controller.'.action.item.post', ['id' => $id, 'name' => $method]) }}"
   data-ajax-init="tooltip, tableShowOnly"
   data-table-hide="2"
   style="color: #2a2c36;"
>
    {{ $text }}
</a>
