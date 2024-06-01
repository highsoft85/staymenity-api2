<form class="row ajax-form --tab-meta-tags-form" action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionMetaTagsSave']) }}">
    <div class="col-12">
        <button role="button" class="btn btn-primary ajax-link mb-1"
                action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionMetaTagsAutoGenerate']) }}"
                data-view=".--tab-meta-tags-form"
                data-callback="replaceView"
                data-loading="1"
                data-ajax-init="textarea-limit"
        >
            Сгенерировать
        </button>
    </div>
    <div class="col-12">
        <div class="form-group">
            @include('cmf.content.default.form.default', [
                'name' => 'meta_tag[title]',
                'field' => [
                    \App\Cmf\Core\FieldParameter::TYPE => \App\Cmf\Core\MainController::DATA_TYPE_TEXT,
                    \App\Cmf\Core\FieldParameter::TITLE => 'Заголовок',
                    \App\Cmf\Core\FieldParameter::DEFAULT => $oItem->metaTag->title ?? '',
                ]
            ])
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            @include('cmf.content.default.form.default', [
                'name' => 'meta_tag[description]',
                'id' => 'meta_tag_description',
                'field' => [
                    \App\Cmf\Core\FieldParameter::TYPE => \App\Cmf\Core\MainController::DATA_TYPE_TEXTAREA,
                    \App\Cmf\Core\FieldParameter::TITLE => 'Описание',
                    \App\Cmf\Core\FieldParameter::DEFAULT => $oItem->metaTag->description ?? '',
                    \App\Cmf\Core\FieldParameter::LIMIT => 140,
                ]
            ])
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            @include('cmf.content.default.form.default', [
                'name' => 'meta_tag[keywords]',
                'field' => [
                    \App\Cmf\Core\FieldParameter::TYPE => \App\Cmf\Core\MainController::DATA_TYPE_TEXTAREA,
                    \App\Cmf\Core\FieldParameter::TITLE => 'Ключевые слова',
                    \App\Cmf\Core\FieldParameter::DEFAULT => $oItem->metaTag->keywords ?? '',
                ]
            ])
        </div>
    </div>
</form>
