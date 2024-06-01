<?php
/** @var \App\Models\User $oItem */
?>
<div class="hr-label">
    <label>transform</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit($oItem->calendarDates()->get()->toArray()) }}
</div>
<div class="hr-label">
    <label>transform</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit(cmfToInvoke($oItem, App\Http\Controllers\Api\User\Calendar\Index::class, null, [])['data']) }}
</div>
