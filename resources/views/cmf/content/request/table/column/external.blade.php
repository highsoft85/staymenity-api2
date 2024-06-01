<?php
/** @var \App\Models\Request $oItem */
?>
@if(isset($oItem->externalArray['city']))
    <p class="m-0">
        <small>
            City: {{ $oItem->externalArray['city'] }}
        </small>
    </p>
@endif
@if(isset($oItem->externalArray['type']))
    <p class="m-0">
        <small>
            Type: {{ $oItem->externalArray['type'] }}
        </small>
    </p>
@endif
