<?php
/** @var \App\Models\Listing $oItem */
/** @var \App\Models\User $oUser */
$oUser = $oItem->userTrashed;
?>
@if(!is_null($oUser))
    <div>
        @include('cmf.components.user.avatar', [
            'oItem' => $oUser,
            'model' => $model,
            'withoutTitle' => true,
        ])
    </div>
@endif
