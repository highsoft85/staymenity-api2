<?php
/** @var string $name */
/** @var string $email */
/** @var string $city */
/** @var string $type */
?>
@extends('layouts.email')

@section('content')
    @component('emails.components.container')
        <h2 class="mb-3">Request form {{ $name }}</h2>
        <p>
            Email: {{ $email }} <br>
            City, State, ZIP: {{ $city }} <br>
            Type of space: {{ $type }}
        </p>
    @endcomponent
@endsection
