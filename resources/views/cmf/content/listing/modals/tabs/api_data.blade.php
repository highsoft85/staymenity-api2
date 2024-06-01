<div class="hr-label">
    <label>transform</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit((new \App\Http\Transformers\Api\ListingTransformer())->transform($oItem)) }}
</div>
<div class="hr-label">
    <label>transformCard</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit((new \App\Http\Transformers\Api\ListingTransformer())->transformCard($oItem)) }}
</div>
<div class="hr-label">
    <label>transformDetail</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit((new \App\Http\Transformers\Api\ListingTransformer())->transformDetail($oItem)) }}
</div>
<div class="hr-label">
    <label>transformImages</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit(cmfToInvoke($oItem->user, App\Http\Controllers\Api\User\Listings\Images\Index::class, null, [], $oItem->id)['data']) }}
</div>
