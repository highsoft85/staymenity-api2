<?php
/** @var \App\Models\User $oItem */
?>
<div class="hr-label">
    <label>transform</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit(cmfToInvoke($oItem, App\Http\Controllers\Api\User\Reviews\Index::class, null, [])['data'])
    }}
</div>
