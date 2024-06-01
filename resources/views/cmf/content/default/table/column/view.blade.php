<?php
$model = isset($model) ? $model : $model;
?>
<a class="trigger btn btn-info btn-sm btn-block --is-show" data-dialog="#custom-edit-modal" data-ajax
   data-action="{{ $url ?? routeCmf($model.'.show.modal.post', ['id' => $oItem->id]) }}"
   data-ajax-init="tooltip"
   data-edit="{{ $oItem->id }}"
        {{--data-id="123"--}}
>
    <i class="icon-eye" style="color: #fff;"></i>
</a>
