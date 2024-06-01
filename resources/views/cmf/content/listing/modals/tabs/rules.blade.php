<?php
/** @var \App\Models\Listing $oItem */
/** @var \App\Models\Rule[] $oRules */
?>
<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionSaveRules', 'id' => $oItem->id]) }}">
    @foreach($oRules as $oRule)
        <div class="col-12">
            <div class="form-group">
                <div class="abc-checkbox">
                    <input type="checkbox" id="rule-{{ $oRule->id }}" class="styled" name="rules[]" value="{{ $oRule->id }}" {{ $oRule->hasRule ? 'checked' : '' }}>
                    <label for="rule-{{ $oRule->id }}">{{ $oRule->title }}</label>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-12">
        <div class="form-group">
            <label>Other description</label>
            <textarea class="form-control" name="rules_other" cols="15" rows="5" placeholder="Other description">{{ $oItem->settings->rules ?? '' }}</textarea>
        </div>
    </div>
</form>
