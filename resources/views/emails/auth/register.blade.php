@extends('layouts.email')

@section('content')
    @component('emails.components.container')
        <h2 class="mb-3">Welcome {{ $oUser->first_name }}</h2>
        <p>
{{--            <a href="{{ route('app.auth.email.confirm', ['token' => $token]) }}">Confirm Email</a>--}}
            <a href="#">Confirm Email</a>
        </p>
    @endcomponent
@endsection
