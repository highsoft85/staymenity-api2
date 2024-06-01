<?php
/** @var \App\Models\User $oItem */
?>
<div class="hr-label">
    <label>transform UPCOMING</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit(cmfToInvoke($oItem, App\Http\Controllers\Api\User\Reservations\Index::class, App\Http\Requests\Api\User\Reservations\IndexRequest::class, [
            'type' => \App\Models\Reservation::SEARCH_TYPE_UPCOMING,
        ])['data'])
    }}
</div>
<div class="hr-label">
    <label>transform CANCELLED</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit(cmfToInvoke($oItem, App\Http\Controllers\Api\User\Reservations\Index::class, App\Http\Requests\Api\User\Reservations\IndexRequest::class, [
            'type' => \App\Models\Reservation::SEARCH_TYPE_CANCELLED,
        ])['data'])
    }}
</div>
<div class="hr-label">
    <label>transform PREVIOUS</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit(cmfToInvoke($oItem, App\Http\Controllers\Api\User\Reservations\Index::class, App\Http\Requests\Api\User\Reservations\IndexRequest::class, [
            'type' => \App\Models\Reservation::SEARCH_TYPE_PREVIOUS,
        ])['data'])
    }}
</div>
