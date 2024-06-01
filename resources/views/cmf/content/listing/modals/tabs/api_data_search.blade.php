<?php
/** @var \App\Models\Listing $oItem */
?>
<div class="hr-label">
    <label>calendar</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit((new \App\Services\Calendar\UserCalendarService($oItem->user))->setListing($oItem)->dates()) }}
</div>
<div class="hr-label">
    <label>times for {{ now()->format('d.m.Y') }}</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit((new \App\Services\Model\ListingTimesService())->getTimes($oItem, now())) }}
</div>
