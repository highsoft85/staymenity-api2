<form class="row ajax-form --tab-source-form" action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionSourceSave']) }}">
    <div class="col-12">
        <div class="form-group">
            @include('cmf.content.default.form.default', [
                'name' => 'source[organization_id]',
                'field' => [
                    \App\Cmf\Core\FieldParameter::TYPE => \App\Cmf\Core\MainController::DATA_TYPE_SELECT,
                    \App\Cmf\Core\FieldParameter::TITLE => 'Организатор',
                    \App\Cmf\Core\FieldParameter::RELATIONSHIP => \App\Cmf\Core\MainController::RELATIONSHIP_BELONGS_TO,
                    \App\Cmf\Core\FieldParameter::VALUES => \App\Models\Organization::class,
                    \App\Cmf\Core\FieldParameter::ORDER => [
                        \App\Cmf\Core\FieldParameter::ORDER_METHOD => 'orderBy',
                        \App\Cmf\Core\FieldParameter::ORDER_BY => 'title',
                    ],
                    \App\Cmf\Core\FieldParameter::ALIAS => 'title',
                    \App\Cmf\Core\FieldParameter::EMPTY => true,
                    'selected_values' => !is_null($oItem->source) && !is_null($oItem->source->organization_id) ? [$oItem->source->organization_id] : [],
                    'values' => \App\Models\Organization::orderBy('title')->get()->pluck('title', 'id'),
                ]
            ])
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            @include('cmf.content.default.form.default', [
                'name' => 'source[url]',
                'field' => [
                    \App\Cmf\Core\FieldParameter::TYPE => \App\Cmf\Core\MainController::DATA_TYPE_TEXT,
                    \App\Cmf\Core\FieldParameter::TITLE => 'Ссылка',
                    \App\Cmf\Core\FieldParameter::DEFAULT => $oItem->source->url ?? '',
                ]
            ])
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            @include('cmf.content.default.form.default', [
                'name' => 'source[description]',
                'field' => [
                    \App\Cmf\Core\FieldParameter::TYPE => \App\Cmf\Core\MainController::DATA_TYPE_TEXTAREA,
                    \App\Cmf\Core\FieldParameter::TITLE => 'Описание',
                    \App\Cmf\Core\FieldParameter::DEFAULT => $oItem->source->description ?? '',
                ]
            ])
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            @include('cmf.content.default.form.default', [
                'name' => 'source[published_at]',
                'field' => [
                    'title' => 'Дата публикации',
                    'dataType' => App\Cmf\Core\MainController::DATA_TYPE_DATE,
                    'date' => true,
                    'placeholder' => 'XX.XX.XXXX',
                    \App\Cmf\Core\FieldParameter::DEFAULT => !is_null($oItem->source) && !is_null($oItem->source->published_at)
                        ? $oItem->source->published_at->format('d.m.Y')
                        : '',
                ]
            ])
        </div>
    </div>
</form>
