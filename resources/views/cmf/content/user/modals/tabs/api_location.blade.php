<?php
/** @var \App\Models\User $oItem */
?>
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
