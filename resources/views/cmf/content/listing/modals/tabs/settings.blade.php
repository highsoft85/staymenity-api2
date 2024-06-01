<?php
/** @var \App\Models\Listing $oItem */
?>
<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionSaveSettings', 'id' => $oItem->id]) }}">
    <div class="col-12">
        <div class="form-group">
            <label>Type</label>
            <input type="text" class="form-control" name="type" placeholder="Type" value="{{ $oItem->settings->type ?? '' }}">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Amenities</label>
            <textarea class="form-control" name="amenities" cols="15" rows="5" placeholder="Amenities">{{ $oItem->settings->amenities ?? '' }}</textarea>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Rules</label>
            <textarea class="form-control" name="rules" cols="15" rows="5" placeholder="Rules">{{ $oItem->settings->rules ?? '' }}</textarea>
        </div>
    </div>
</form>
