@include('cmf.components.gallery.gallery', [
    'model' => $model,
    'oItem' => $oItem,
    'type' => \App\Services\Image\ImageType::MODEL,
    'images' => $oItem->modelImages,
])
