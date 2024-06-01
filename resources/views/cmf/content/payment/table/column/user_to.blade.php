<?php
/** @var \App\Models\Payment $oItem */
/** @var \App\Models\User $oUser */
$oUser = $oItem->userToTrashed;
?>
@if(!is_null($oUser))
    <div class="d-flex align-items-center justify-content-start" style="width: 200px">
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
