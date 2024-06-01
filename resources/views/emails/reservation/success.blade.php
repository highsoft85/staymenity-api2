<?php
/** @var \App\Models\User $oUser */
/** @var \App\Models\Reservation $oReservation */
?>

@extends('layouts.email')

@section('content')
    @component('emails.components.container')
        <h2 class="mb-3">Welcome {{ $oUser->first_name }}</h2>
        <p>
            Reservation date: {{ $oReservation->paymentDescriptionDate }} ({{ $oReservation->hours }} hours) <br>
            Guests: {{ $oReservation->guests_size }} <br>
            Reservation code: {{ $oReservation->code }} <br>
            Listing: {{ $oReservation->listing->title }} <br>
            @if(!is_null($oReservation->listing->location))
                Address: {{ (new \App\Http\Transformers\Api\LocationTransformer())->getAddress($oReservation->listing->location) }}
            @endif
        </p>
        <p>
            Free cancellation before {{ $oReservation->free_cancellation_at->format('m-d-Y H:i') }}
        </p>
    @endcomponent
@endsection
