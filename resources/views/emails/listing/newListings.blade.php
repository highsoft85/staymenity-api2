<?php
/** @var \App\Models\Listing[] $oListings */
?>
@extends('layouts.email')

@section('content')
    @component('emails.components.container')
        <h2 class="mb-3">New Listings</h2>
        @foreach($oListings as $oListing)
            <p>
                ID: {{ $oListing->id }}
                <br>
                Title: {{ $oListing->title }}
                <br>
                Host: {{ $oListing->userTrashed->fullName }}
                <br>
                Address: {{ $oListing->location->address ?? 'None' }}
                <br>
                Created At: {{ $oListing->created_at->format('m/d/Y H:i:s') }}
                <br>
                <a href="{{ $oListing->getUrl() }}" target="_blank">{{ $oListing->getUrl() }}</a>
            </p>
            @if(!$loop->last)
                <hr>
            @endif
        @endforeach
    @endcomponent
@endsection
