@php
    $model = isset($model) ? $model : $view;
@endphp

@include('cmf.components.text.text', [
    'model' => $model,
    'oItem' => $oItem,
    'form' => '#'.$model.'-form',
    'itemText' => $oItem->description,
])
