<?php
$model = isset($model) ? $model : $model;
?>
<a class="btn btn-sm trigger --is-show" data-dialog="#custom-edit-modal" data-ajax
   data-action="{{ $url ?? routeCmf($model.'.show.modal.post', ['id' => $oItem->id]) }}"
   data-ajax-init="tooltip"
   data-edit="{{ $oItem->id }}"
        {{--data-id="123"--}}
>
    <i class="fa fa-eye" aria-hidden="true"></i>
</a>
