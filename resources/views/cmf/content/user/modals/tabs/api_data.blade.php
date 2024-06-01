<div class="hr-label">
    <label>transform</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit((new \App\Http\Transformers\Api\UserTransformer())->transform($oItem)) }}
</div>
<div class="hr-label">
    <label>transformDetail</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit((new \App\Http\Transformers\Api\UserTransformer())->transformDetail($oItem)) }}
</div>
<div class="hr-label">
    <label>transformHost</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit((new \App\Http\Transformers\Api\UserTransformer())->transformHost($oItem)) }}
</div>
<div class="hr-label">
    <label>transformMention</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit((new \App\Http\Transformers\Api\UserTransformer())->transformMention($oItem)) }}
</div>
