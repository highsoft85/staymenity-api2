<?php
/** @var \App\Models\Listing $oItem */
?>
<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionSaveAddress', 'id' => $oItem->id]) }}">
    <div class="col-12">
        <div class="form-group">
            <label>Address</label>
            <input type="text" class="form-control" name="address" value="{{ $oItem->location->address ?? '' }}">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <button class="btn btn-primary inner-form-submit" type="submit">
                Save
            </button>
        </div>
    </div>
</form>
@if(!is_null($oItem->location) && config('services.yandex_map.enabled'))
    @include('cmf.content.default.modals.tabs.locations.map', [
        'model' => 'listing',
        'oLocation' => $oItem->location,
        'oItem' => $oItem,
    ])
@endif
<div class="hr-label">
    <label>transform</label>
    <hr>
</div>
@if(!is_null($oItem->location))
    <div>
        {{ ddWithoutExit((new \App\Http\Transformers\Api\LocationTransformer())->transform($oItem->location)) }}
    </div>
@endif
