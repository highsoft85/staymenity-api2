{{--
    'priorityUrl' => url('/admin/posts/action/save-gallery-priority'),
    'uploadUrl' => url('/admin/posts/action/upload-gallery'),
    'model' => 'post',
    'oItem' => $oPost,
--}}
<div id="gallery-container" class="gallery-container is-{{ $type }}-type" data-id="file-image-{{ $model }}-{{ $type }}-{{ $oItem->id }}">
    <div class="row">
        <div class="col-12">
            <div id="dropzone" class="dropzone fade well" data-id="file-image-{{ $model }}-{{ $type }}-{{ $oItem->id }}">
                <i class="fa fa-plus" style="margin-right: 10px;"></i>
                Drop your files here, or click to select them manually!
            </div>
        </div>
    </div>
    <div class="row is-gallery-row"
         data-save-action="{{ routeCmf($model.'.action.post', ['name' => 'imagePriority']) }}"
         data-name="image-priority"
         data-item_id="{{ $oItem->id }}"
         data-type="{{ $type }}"
         data-col="{{ $col ?? 3 }}"
    >
        @include('cmf.components.gallery.block', [
            'oItem' => $oItem,
            'col' => $col ?? 3,
            'model' => $model,
            'images' => $images,
            'type' => $type,
        ])
    </div>
    <div class="row hidden" style="margin-top: 15px;">
        <div class="col-12">
            <input type="file" name="images[]" id="file-image-{{ $model }}-{{ $type }}-{{ $oItem->id }}" class="btn btn-primary file-uploader is-file" accept="image/*"
                   data-dropzone='.dropzone[data-id="file-image-{{ $model }}-{{ $type }}-{{ $oItem->id }}"]'
                   data-url="{{ routeCmf($model.'.image.upload.post', ['id' => $oItem->id]) }}"
                   data-id="{{ $oItem->id }}"
                   data-uid=""
                   data-col="{{ $col ?? 3 }}"
                   data-type="{{ $type }}"

                   data-loading-container='.gallery-container[data-id="file-image-{{ $model }}-{{ $type }}-{{ $oItem->id }}"]'
                   multiple
            >
        </div>
    </div>
</div>
