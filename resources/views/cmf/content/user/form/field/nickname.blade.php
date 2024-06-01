<div class="input-group">
    <div class="input-group-prepend bg-default">
        <span class="input-group-text">
            @
        </span>
    </div>
    @include('cmf.content.default.form.default', [
        'item' => $oItem,
        'field' => $field,
        'no_title' => true,
        'dontInclude' => true,
    ])
</div>
