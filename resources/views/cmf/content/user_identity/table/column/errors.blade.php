<?php
/** @var \App\Models\UserIdentity $oItem */
?>
@foreach($oItem->errorsArray as $key => $error)
    <a class="btn btn-sm btn-tiny" role="button"
       data-tippy-popover
       data-tippy-content="{{ $key . ': ' . $error }}"
       style="color: #f86c6b;"
    >
        <i class="fa fa-circle" aria-hidden="true"></i>
    </a>
@endforeach
