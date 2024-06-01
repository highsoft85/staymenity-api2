<?php
/** @var \App\Models\Reservation $oItem */
/** @var \App\Models\Listing $oListing */
$oListing = $oItem->listingTrashed;
?>
@if(!is_null($oListing))
    <div class="d-flex align-items-center justify-content-start">
        <div>
            @include('cmf.components.user.avatar', [
                'oItem' => $oListing,
                'model' => $model,
                'withoutTitle' => false,
            ])
        </div>
        <div>
            @if(!is_null($oListing->type) && $oListing->isActive())
                #{{ $oListing->id }}:
                <a href="{{ $oListing->getUrl() }}" target="_blank">
                    {{ $oListing->title }}
                </a>
            @else
                #{{ $oListing->id }}: {{ $oListing->title }}
            @endif
            <br>
            <small>{{ \Illuminate\Support\Str::limit($oListing->description, 30) }}</small>
        </div>
    </div>
@endif
