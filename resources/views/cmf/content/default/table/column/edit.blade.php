<?php
$model = isset($model) ? $model : $model;
?>
<a class="btn btn-primary btn-sm trigger {{ isset($disabled) && $disabled ? 'disabled' : '' }} {{ isset($class) && $class ? $class : '' }} --is-edit"
   data-dialog="#custom-edit-modal" data-ajax
   data-action="{{ $url ?? routeCmf($model.'.edit.modal.post', ['id' => $oItem->id]) }}"
   data-ajax-init="{{ $init ?? '' }}"
   data-edit="{{ $oItem->id }}"
   data-model="{{ $model }}"
        {{--data-id="123"--}}
>
    @if(isset($text))
        <span style="color: #fff;">{{ $text }}</span>
    @else
        {{--<i class="fa {{ $fa or 'fa-pencil'}}" style="color: #fff;"></i>--}}
        <i class="icon-pencil" style="color: #fff;"></i>
    @endif
</a>
