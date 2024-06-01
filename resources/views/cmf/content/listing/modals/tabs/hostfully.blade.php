<?php
/** @var \App\Models\Listing $oItem */
/** @see \App\Cmf\Project\Listing\ListingCustomTrait::actionSaveHostfully */
?>
<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionSaveHostfully', 'id' => $oItem->id]) }}">
    <div class="col-12">
        <div class="form-group">
            <label>PropertyUid</label>
            <input type="text" class="form-control" name="propertyUid" placeholder="Property Uid" value="{{ $oItem->hostfully->uid ?? '' }}">
        </div>
    </div>
    <div class="col-12">
        Set active for property in {company} -> Channels -> Staymenity (click Manage this channel)  <br>
        Channel Status: @if(!is_null($oItem->hostfully) && $oItem->hostfully->is_channel_active === 1) <span class="text-success">Active</span> @else <span class="text-danger">Inactive</span> @endif
    </div>
</form>
