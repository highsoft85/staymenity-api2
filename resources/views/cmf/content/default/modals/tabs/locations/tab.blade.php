<div class="row --coordinates-container">
    <div class="col-12">
        <div class="form-group">
            <label>
                Поиск
                <button class="btn btn-outline-danger btn-sm ajax-link" type="button" action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'coordinatesClear']) }}"
                        data-loading="1"
                        data-view=".--view-coordinates-tabs"
                        data-callback="replaceView"
                        data-ajax-init="coordinates, multiselect"
                        data-location_id="{{ $oLocation->id }}"
                        style="position: absolute;top: 0;right: 15px;font-size: 10px;"
                >
                    Удалить координаты
                </button>
            </label>
            <input class="form-control --geo-input" type="text" name="location[{{ $oLocation->id }}][city]" placeholder="Поиск" value="">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group is-row-group row">
            <div class="form-group col-12" style="margin-bottom: 0;margin-top: 5px;">
                <b>Координаты</b>
                <hr style="margin: 5px 0;">
            </div>
            <div class="form-group col-4">
                <label>Широта</label>
                <input class="form-control --is-latitude" type="text" name="location[{{ $oLocation->id }}][latitude]" placeholder="Широта" value="{{ $oLocation->latitude ?? '' }}">
            </div>
            <div class="form-group col-4">
                <label>Долгота</label>
                <input class="form-control --is-longitude" type="text" name="location[{{ $oLocation->id }}][longitude]" placeholder="Долгота" value="{{ $oLocation->longitude ?? '' }}">
            </div>
            <div class="form-group col-4 is-disabled">
                <label>Масштаб</label>
                <input class="form-control --is-zoom" type="text" name="location[{{ $oLocation->id }}][zoom]" placeholder="Масштаб" value="{{ $oLocation->zoom ?? '' }}">
            </div>
            <div class="form-group col-8">
                <label>Заголовок</label>
                <input class="form-control" type="text" name="location[{{ $oLocation->id }}][title]" placeholder="Заголовок" value="{{ $oLocation->title ?? '' }}">
            </div>
            <div class="form-group col-4">
                @include('cmf.content.default.form.default', [
                    'name' => 'location[' . $oLocation->id . '][type]',
                    'field' => [
                        \App\Cmf\Core\FieldParameter::TYPE => App\Cmf\Core\MainController::DATA_TYPE_SELECT,
                        \App\Cmf\Core\FieldParameter::TITLE => 'Type',
                        \App\Cmf\Core\FieldParameter::REQUIRED => true,
                        \App\Cmf\Core\FieldParameter::VALUES => \App\Models\Location::staticTypes(),
                        \App\Cmf\Core\FieldParameter::SELECTED_VALUES => [$oLocation->type],
                    ]
                ])
            </div>
        </div>
        <div class="form-group is-row-group row">
            <div class="form-group col-12" style="margin-bottom: 0;margin-top: 5px;">
                <b>Карта</b>
                <hr style="margin: 5px 0;">
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="--map-container" id="location_{{ $oLocation->id }}" style="height: 400px"></div>
    </div>
    <div class="col-12">
        <div class="form-group is-row-group row">
            <div class="form-group col-12" style="margin-bottom: 0;margin-top: 5px;">
                <b>Дополнительные параметры</b>
                <hr style="margin: 5px 0;">
            </div>
            <div class="form-group col-6">
                <label>Полное описание</label>
                <input class="form-control --support-text" type="text" name="location[{{ $oLocation->id }}][text]" placeholder="Полное описание" value="{{ $oLocation->text ?? '' }}">
            </div>
            <div class="form-group col-6">
                <label>Адрес объекта</label>
                <input class="form-control --support-address" type="text" name="location[{{ $oLocation->id }}][address]" placeholder="Адрес объекта" value="{{ $oLocation->address ?? '' }}">
            </div>
            <div class="form-group col-6">
                <label>Государство</label>
                <input class="form-control --support-country" type="text" name="location[{{ $oLocation->id }}][country]" placeholder="Государство" value="{{ $oLocation->country ?? '' }}">
            </div>
            <div class="form-group col-6">
                <label>Код</label>
                <input class="form-control --support-country_code" type="text" name="location[{{ $oLocation->id }}][country_code]" placeholder="Код" value="{{ $oLocation->country_code ?? '' }}">
            </div>
            <div class="form-group col-6">
                <label>Штат</label>
                <input class="form-control --support-province" type="text" name="location[{{ $oLocation->id }}][province]" placeholder="Штат" value="{{ $oLocation->province ?? '' }}">
            </div>
            <div class="form-group col-6">
                <label>Населенный пункт</label>
                <input class="form-control --support-locality" type="text" name="location[{{ $oLocation->id }}][locality]" placeholder="Населенный пункт" value="{{ $oLocation->locality ?? '' }}">
            </div>
        </div>
    </div>
</div>
