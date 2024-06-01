<?php
/** @var \App\Models\User $oItem */
?>
<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionMessage', 'id' => $oItem->id]) }}">

    <div class="col-12">
        <div class="form-group">
            <label>Message</label>
            <textarea class="form-control" name="message" cols="15" rows="5" placeholder="Message"></textarea>
        </div>
    </div>
</form>
