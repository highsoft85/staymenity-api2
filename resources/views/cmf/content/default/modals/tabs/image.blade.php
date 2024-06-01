@php
    $model = isset($model) ? $model : $view;
@endphp

@include('cmf.components.gallery.gallery', [
    'priorityUrl' => routeCmf($model.'.action.post', ['name' => 'imagePriority']),
    'uploadUrl' => routeCmf($model.'.image.upload.post', ['id' => $oItem->id]),
    'model' => $model,
    'oItem' => $oItem,
    'form' => '#'.$model.'-form'
])
