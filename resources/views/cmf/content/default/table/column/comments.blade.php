<?php
$model = isset($model) ? $model : $model;
// чтобы не было двух диалогов, которые открываются после создания
// откуда подключается - должно быть изменено это значение, например
// views\admin\content\subscription-request\custom\value\status.blade.php
$isRole = $isRole ?? 'edit';
?>
{{--<a class="btn btn-{{ $btn_type ?? 'primary' }} btn-sm trigger {{ isset($disabled) && $disabled ? 'disabled' : '' }} --is-{{ $isRole }}" data-dialog="#custom-edit-modal" data-ajax--}}
{{--   href="#"--}}
{{--   data-action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'actionCommentsGetModal']) }}"--}}
{{--   data-ajax-init="{{ $init ?? '' }}"--}}
{{--   data-callback="openComments"--}}
{{--   data-edit="{{ $oItem->id }}"--}}
{{--   data-model="{{ $model }}"--}}
{{--    @if(isset($aAttributes))--}}
{{--        @foreach($aAttributes as $key => $value)--}}
{{--            {{ $key . '=' . $value }}--}}
{{--        @endforeach--}}
{{--    @endif--}}
{{-->--}}
{{--    <i class="icon-speech" style="color: #fff;"></i>--}}
{{--</a>--}}
<a class="alert alert-{{ count($oItem->comments) === 0 ? 'default' : 'primary' }} alert-sm text-center trigger" role="alert"
   href="#"
   data-dialog="#custom-edit-modal" data-ajax
   data-action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'actionCommentsGetModal']) }}"
   data-ajax-init="{{ $init ?? '' }}"
   data-callback="openComments"
   data-edit="{{ $oItem->id }}"
   data-model="{{ $model }}"
   style="width: 30px;display: block;"
>
    <span style="font-size: 12px;">{{ count($oItem->comments) }}</span>
</a>
