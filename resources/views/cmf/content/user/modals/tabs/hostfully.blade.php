<?php
/** @var \App\Models\User $oItem */
/** @see \App\Cmf\Project\User\UserCustomTrait::actionSaveHostfully */
?>
<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionSaveHostfully', 'id' => $oItem->id]) }}">
    <div class="col-12">
        <div class="form-group">
            <label>AgencyUid</label>
            <input type="text" class="form-control" name="agencyUid" placeholder="AgencyUid" value="{{ !is_null($oItem->details) ? $oItem->details->hostfully_agency_uid : '' }}"
                   {{ !is_null($oItem->details) && $oItem->details->hostfully_agency_uid !== null ? 'disabled' : '' }}
            >
        </div>
    </div>
    <div class="col-12">
        Set active for property in {company} -> Channels -> Staymenity (click Manage this channel)  <br>
        Channel Status: @if(!is_null($oItem->details) && $oItem->details->hostfully_status === 1) <span class="text-success">Active</span> @else <span class="text-danger">Inactive</span> @endif
    </div>
    @if(!is_null($oItem->details) && $oItem->details->hostfully_agency_uid !== null)
        <div class="col-12 mt-1">
            <a class="btn btn-danger text-white ajax-link"
               action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionRemoveHostfully']) }}"
               data-loading="1"
               data-callback="closeModalAfterSubmit"
            >
                Remove
            </a>
        </div>
    @endif
</form>
