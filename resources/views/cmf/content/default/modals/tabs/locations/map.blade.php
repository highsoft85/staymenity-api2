<div class="row --coordinates-container">
    <div class="col-12 d-none">
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
</div>
