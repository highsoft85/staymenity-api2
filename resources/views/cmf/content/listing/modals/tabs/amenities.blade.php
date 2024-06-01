<?php
/** @var \App\Models\Listing $oItem */
/** @var \App\Models\Amenity[] $oAmenities */
?>
<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionSaveAmenities', 'id' => $oItem->id]) }}">
    @foreach($oAmenities as $oAmenity)
        <div class="col-12">
            <div class="form-group">
                <div class="abc-checkbox">
                    <input type="checkbox" id="amenity-{{ $oAmenity->id }}" class="styled" name="amenities[]" value="{{ $oAmenity->id }}" {{ $oAmenity->hasAmenity ? 'checked' : '' }}>
                    <label for="amenity-{{ $oAmenity->id }}">{{ $oAmenity->title }}</label>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-12">
        <div class="form-group">
            <label>Other description</label>
            <textarea class="form-control" name="amenities_other" cols="15" rows="5" placeholder="Other description">{{ $oItem->settings->amenities ?? '' }}</textarea>
        </div>
    </div>
</form>
