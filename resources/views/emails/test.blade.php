@extends('layouts.email')

@section('content')
    @component('emails.components.container')
        <h2 class="mb-3">{{ $title }}</h2>
    @endcomponent
@endsection
