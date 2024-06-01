<form class="row ajax-form"
      action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionPriceSave']) }}"
>
    <div class="form-group col-6">
        @include('cmf.content.default.form.default', [
            'name' => 'price[value]',
            'field' => [
                \App\Cmf\Core\FieldParameter::TYPE => App\Cmf\Core\MainController::DATA_TYPE_NUMBER,
                \App\Cmf\Core\FieldParameter::TITLE => 'Цена',
                \App\Cmf\Core\FieldParameter::LENGTH => 6,
                \App\Cmf\Core\FieldParameter::DEFAULT => $oPrice->value ?? '',
            ]
        ])
    </div>
    <div class="form-group col-6">
        @include('cmf.content.default.form.default', [
            'name' => 'price[value_description]',
            'field' => [
                \App\Cmf\Core\FieldParameter::TYPE => App\Cmf\Core\MainController::DATA_TYPE_TEXTAREA,
                \App\Cmf\Core\FieldParameter::TITLE => 'Описание',
                \App\Cmf\Core\FieldParameter::DEFAULT => $oPrice->value_description ?? '',
            ]
        ])
    </div>
    <div class="form-group col-3">
        @include('cmf.content.default.form.default', [
            'name' => 'price[discount]',
            'field' => [
                \App\Cmf\Core\FieldParameter::TYPE => App\Cmf\Core\MainController::DATA_TYPE_NUMBER,
                \App\Cmf\Core\FieldParameter::TITLE => 'Цена со скидкой',
                \App\Cmf\Core\FieldParameter::LENGTH => 6,
                \App\Cmf\Core\FieldParameter::TOOLTIP => 'Старая цена будет перечеркнута',
                \App\Cmf\Core\FieldParameter::DEFAULT => $oPrice->discount ?? '',
            ]
        ])
    </div>
    <div class="form-group col-3">
        @include('cmf.content.default.form.default', [
            'name' => 'price[discount_before_at]',
            'field' => [
                'title' => 'Цена действует до',
                'dataType' => App\Cmf\Core\MainController::DATA_TYPE_DATE,
                'date' => true,
                'default' => !is_null($oPrice) && !is_null($oPrice->discount_before_at) ? $oPrice->discount_before_at->format('d.m.Y') : '',
                'placeholder' => 'XX.XX.XXXX',
            ]
        ])
    </div>
    <div class="form-group col-6">
        @include('cmf.content.default.form.default', [
            'name' => 'price[discount_description]',
            'field' => [
                \App\Cmf\Core\FieldParameter::TYPE => App\Cmf\Core\MainController::DATA_TYPE_TEXTAREA,
                \App\Cmf\Core\FieldParameter::TITLE => 'Описание',
                \App\Cmf\Core\FieldParameter::DEFAULT => $oPrice->discount_description ?? '',
            ]
        ])
    </div>
</form>
