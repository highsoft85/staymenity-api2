<?php
/** @var \App\Models\UserIdentity $oItem */
/** @var \App\Models\User $oUser */
$oUser = $oItem->userTrashed;
?>
@if(!is_null($oUser))
    <div class="d-flex align-items-center justify-content-start">
        <div>
            @include('cmf.components.user.avatar', [
                'oItem' => $oUser,
                'model' => $model,
            ])
        </div>
        <div>
            #{{ $oUser->id }}: {{ $oUser->fullName }}
            <br>
            <small>{{ $oUser->email }}</small>
        </div>
    </div>
@endif
