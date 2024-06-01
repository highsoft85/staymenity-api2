<?php
$col = $col ?? 3;
$size = 'square';
?>

@foreach($images as $image)
    <div class="col-{{ $col }}" style="position: relative; margin-top: 10px;">
        <img src="{{ imagePath($model, $size, $image, $type) }}"
             class="drag-image {{-- drag-pointer --}}"
             data-id="{{ $image->id }}"
             style="width: 100%;"
        >
        @if(!is_null($image->number))
            <span class="text-muted" style="position: absolute;top: 4px;left: 4px;font-size: 12px;">{{ $image->number }}</span>
        @endif
        @if($type === \App\Services\Image\ImageType::MODEL)
            <button type="button" class="btn btn-sm icon __l-t ajax-link {{ intval($image->is_main) === 1 ? 'btn-primary' : 'btn-outline-primary' }}"
                    action="{{ routeCmf($model.'.image.main.post', ['id' => $oItem->id, 'image_id' => $image->id]) }}"
                    data-image_id="{{ $image->id }}"
                    data-loading="1"
                    data-view='.is-gallery-row[data-type="{{ $type }}"]'
                    data-callback="updateView"
                    title="Set Main"
            >
                <i class="icon-check"></i>
            </button>
        @endif
        <button type="button" class="btn btn-sm icon __r-t btn-outline-danger ajax-link"
                action="{{ routeCmf($model.'.image.destroy.post', ['id' => $oItem->id, 'image_id' => $image->id]) }}"
                data-image_id="{{ $image->id }}"
                data-loading="1"
                data-view='.is-gallery-row[data-type="{{ $type }}"]'
                data-callback="updateView"
                title="Delete"
        >
            <i class="icon-trash"></i>
        </button>
        <button type="button" class="btn btn-sm icon __c-b btn-outline-primary" data-fancybox="gallery-post-{{$oItem->id}}" href="{{ imagePath($model, 'original', $image, $type) }}"
                title="Show"
        >
            <i class="icon-eye"></i>
        </button>
        {{--<button type="button" class="btn btn-sm icon __l-b btn-outline-success"--}}
        {{--data-toggle="popover"--}}
        {{--data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'--}}
        {{--data-content='@include('admin.components.gallery.popover.author')'--}}
        {{--data-html="true"--}}
        {{--data-container=".is-gallery-row"--}}

        {{-->--}}
        {{--<i class="icon-link"></i>--}}
        {{--</button>--}}
        {{--<button type="button" class="btn btn-sm icon __r-b btn-outline-success trigger" data-dialog="#pages-dialogs-ajax" data-ajax--}}
                {{--data-action="{{ routeCmf('info.action.item.post', ['id' => $oItem->id, 'action' => 'getLinkImagesModal', 'image_id' => $image->id, 'model' => $model]) }}"--}}
                {{--title="Ссылки на изображения"--}}
        {{-->--}}
            {{--<i class="icon-link"></i>--}}
        {{--</button>--}}
{{--        <button type="button" class="btn btn-sm icon __r-b btn-outline-success trigger" title="Источник"--}}
{{--                data-dialog="#custom-edit-modal-support"--}}
{{--                data-ajax--}}
{{--                data-action="{{ routeCmf($model.'.action.item.post', ['id' => $image->id, 'name' => 'imageSupportModal']) }}"--}}
{{--                data-tab="source"--}}
{{--                data-model="{{$model}}"--}}
{{--        >--}}
{{--            <i class="icon-paper-clip"></i>--}}
{{--        </button>--}}
{{--        <button type="button" class="btn btn-sm icon __l-b btn-outline-success trigger" title="Информация об изображении"--}}
{{--                data-dialog="#custom-edit-modal-support"--}}
{{--                data-ajax--}}
{{--                data-action="{{ routeCmf($model.'.action.item.post', ['id' => $image->id, 'name' => 'imageSupportModal']) }}"--}}
{{--                data-tab="info"--}}
{{--                data-model="{{$model}}"--}}
{{--        >--}}
{{--            <i class="icon-info"></i>--}}
{{--        </button>--}}
        {{--<input id="clipboard-{{ $image->id }}" type="text" value="{{ ImagePath::image($model, 'original', $image) }}" style="display: none;">--}}
    </div>
@endforeach
<div class="col-{{ $col }} --empty-placeholder" style="position: relative; margin-top: 10px; height: 79px;"></div>
