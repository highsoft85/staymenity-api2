<?php
/** @var \App\Models\User $oUser */
?>
@extends('layouts.email')

@section('content')
    @component('emails.components.container')
        <h2 class="mb-3">Hello {{ $oUser->first_name }}</h2>
        <p>
            You Have a New Message on Staymenity
            <br>
            <a href="{{ route('web.messages') }}">Messages</a>
        </p>
    @endcomponent
@endsection
