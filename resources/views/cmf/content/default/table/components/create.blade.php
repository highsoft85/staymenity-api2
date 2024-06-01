<button class="btn btn-primary float-right trigger"
        data-ajax
        data-action="{{ routeCmf($model.'.create.modal.post') }}"
        data-dialog="#custom-edit-modal"
        data-ajax-init="{{ $init ?? '' }}"
>
    <i class="fa fa-plus" style="margin-right: 10px;"></i>
    Create
</button>
