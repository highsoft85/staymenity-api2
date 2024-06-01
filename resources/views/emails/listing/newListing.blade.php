<?php
/** @var \App\Models\Listing $oListing */
?>
@extends('layouts.email')

@section('content')
    @component('emails.components.container')
        <h2 class="mb-3">New Listing</h2>
        <p>
            Title: {{ $oListing->title }}
            <br>
            Host: {{ $oListing->userTrashed->fullName }}
            <br>
            Address: {{ $oListing->location->address ?? 'None' }}
            <br>
            Created At: {{ $oListing->created_at->format('m/d/Y H:i:s') }}
        </p>
    @endcomponent
@endsection
