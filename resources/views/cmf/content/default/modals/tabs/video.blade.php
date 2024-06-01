<form class="ajax-form"
      action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionVideoSave']) }}"
>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="">Ссылка на видео с Ютуб</label>
                <input type="text" class="form-control --fancybox-video-input" name="video" placeholder="Видео" value="{{ $oItem->parameter_video_youtube ?? '' }}"
                    data-link="#video-link-{{ $model }}-{{ $oItem->id }}"
                    data-iframe="#video-iframe-{{ $model }}-{{ $oItem->id }}"
                >
            </div>
        </div>
        <div class="col-12">
            <a class="btn btn-primary" id="video-link-{{ $model }}-{{ $oItem->id }}" data-fancybox href="">
                Посмотреть видео
            </a>
        </div>
        <div class="col-12 mt-1 mb-1">
            <iframe width="560" height="315"
                    id="video-iframe-{{ $model }}-{{ $oItem->id }}"
                    src="" frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen
            ></iframe>
        </div>
    </div>
</form>

