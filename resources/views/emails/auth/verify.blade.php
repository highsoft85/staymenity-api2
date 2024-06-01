<?php
/** @var \App\Models\User $oUser */
/** @var string $token */
?>

@extends('layouts.email')

@section('content')
    @component('emails.components.container')
        <h2 class="mb-5 text-center">Verify Your Email</h2>
        <p class="text-center">
            Please verify your email to secure your account.
        </p>
        <div class="btn mb-5" style="text-align: center;">
            <a href="{{ route('web.auth.verify.success', ['token' => $token, 'email' => $oUser->email]) }}" class="btn text-center text-muted" style="width: 160px;">
                VERIFY NOW
            </a>
        </div>
        <div>
            <p class="text-center">
                If this wasn't you, please <a href="{{ route('web.auth.verify.failed', ['token' => $token, 'email' => $oUser->email]) }}">click here</a>
            </p>
        </div>
    @endcomponent
@endsection
