<?php
/** @var \App\Models\User $oItem */
?>
<div class="d-flex align-items-center justify-content-start" style="width: 200px">
    <div>
        @include('cmf.components.user.avatar', [
            'oItem' => $oItem,
            'model' => $model,
        ])
    </div>
    <div>
        {{ $oItem->first_name }} {{ $oItem->last_name }}
        <br>
        <small>{{ $oItem->email }}</small>
    </div>
</div>
