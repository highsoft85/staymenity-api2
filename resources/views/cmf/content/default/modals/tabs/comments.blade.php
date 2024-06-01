<?php
    $type = $type ?? 'edit';
?>
<div class="row">
    <div class="col-12">
        @if(isset($type) && $type === 'edit')
            <div>
                @include('cmf.components.comments.form', [
                    'oItem' => $oItem,
                ])
            </div>
            <br>
        @endif
        <div class="mb-5 --comments-field-container">
            @include('cmf.components.comments.comments', [
                'oItem' => $oItem,
                'type' => $type ?? null,
            ])
        </div>
    </div>
</div>



