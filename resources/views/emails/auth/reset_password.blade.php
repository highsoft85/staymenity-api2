<?php
/** @var \App\Models\User $oUser */
/** @var string $token */
?>

@extends('layouts.email')

@section('content')
    @component('emails.components.container')
        <h2 class="mb-5 text-center">Reset Your Password</h2>
        <div class="mb-5">
            <p class="text-center">
                Please follow the
                <a href="{{ route('web.auth.password.reset', ['token' => $token, 'email' => $oUser->email]) }}">link</a>
                to proceed with reset password.
            </p>
        </div>
        <div>
            <p class="text-center">
                If this wasn't you, please
                <a href="{{ route('web.auth.verify.failed', ['token' => $token, 'email' => $oUser->email]) }}">
                    click here
                </a>
            </p>
        </div>
    @endcomponent
@endsection
