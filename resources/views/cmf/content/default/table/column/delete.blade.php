<?php
$model = isset($model) ? $model : $model;
?>
<a class="btn btn-danger btn-sm is-small trigger @if(isset($disabled) && $disabled) disabled @endif" data-dialog="#pages-dialogs-confirm" data-confirm
   data-text='Are you sure you want to delete #{{$oItem->id}}: {{$deleteKey}}?'
   data-action="{{ routeCmf($model.'.destroy', ['id' => $oItem->id]) }}"
   data-list-action="{{ routeCmf($model.'.view.post') }}"
   @if(isset($subtitle))
   data-subtitle="{{ $subtitle }}"
        @endif
>
    <i class="icon-trash icons" style="color: #fff;"></i>
    {{--<i class="fa fa-trash" style="color: #fff;"></i>--}}
</a>
