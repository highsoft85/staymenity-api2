@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.keys'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Keys',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('services.google_maps.enabled') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Google Maps
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="Web"><i class="fa fa-circle text-warning"></i></span>
                        <span data-tippy-popover data-tippy-content="iOS"><i class="fa fa-circle text-danger"></i></span>
                        <span data-tippy-popover data-tippy-content="Backend"><i class="fa fa-circle text-info"></i></span>
                    </p>
                    <p class="m-0 m-t-1">
                        Key: <code>{{ config('services.google_maps.key') }}</code>
                    </p>
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('services.google.client_id') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Google Auth CLIENT_ID
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="Web"><i class="fa fa-circle text-warning"></i></span>
                        <span data-tippy-popover data-tippy-content="iOS"><i class="fa fa-circle text-danger"></i></span>
                        <span data-tippy-popover data-tippy-content="Backend"><i class="fa fa-circle text-info"></i></span>
                    </p>
                    <p class="m-0 m-t-1">
                        Key: <code>{{ config('services.google.client_id') }}</code>
                    </p>
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('services.google.ios_client_id') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Google Auth IOS CLIENT_ID
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="iOS"><i class="fa fa-circle text-danger"></i></span>
                    </p>
                    <p class="m-0 m-t-1">
                        Key: <code>{{ config('services.google.ios_client_id') }}</code>
                    </p>
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('services.google_analytics.enabled') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Google Analytics
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="Web"><i class="fa fa-circle text-warning"></i></span>
                    </p>
                    <p class="m-0 m-t-1">
                        Key: <code>{{ config('services.google_analytics.key') }}</code>
                    </p>
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('services.facebook.client_id') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Facebook Auth CLIENT_ID
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="Web"><i class="fa fa-circle text-warning"></i></span>
                        <span data-tippy-popover data-tippy-content="iOS"><i class="fa fa-circle text-danger"></i></span>
                        <span data-tippy-popover data-tippy-content="Backend"><i class="fa fa-circle text-info"></i></span>
                    </p>
                    <p class="m-0 m-t-1">
                        Key: <code>{{ config('services.facebook.client_id') }}</code>
                    </p>
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('services.apple.client_id') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Apple Auth CLIENT_ID
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="Web"><i class="fa fa-circle text-warning"></i></span>
                        <span data-tippy-popover data-tippy-content="iOS"><i class="fa fa-circle text-danger"></i></span>
                        <span data-tippy-popover data-tippy-content="Backend"><i class="fa fa-circle text-info"></i></span>
                    </p>
                    <p class="m-0 m-t-1">
                        Key: <code>{{ config('services.apple.client_id') }}</code>
                    </p>
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('services.stripe.public_key') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Stripe
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="Web"><i class="fa fa-circle text-warning"></i></span>
                        <span data-tippy-popover data-tippy-content="iOS"><i class="fa fa-circle text-danger"></i></span>
                        <span data-tippy-popover data-tippy-content="Backend"><i class="fa fa-circle text-info"></i></span>
                    </p>
                    <p class="m-0 m-t-1">
                        STRIPE_TEST_PUBLISHABLE_KEY: <code>{{ config('services.stripe.test_public_key') }}</code>
                    </p>
                    <p class="m-0 m-t-1">
                        STRIPE_PUBLISHABLE_KEY: <code>{{ config('services.stripe.public_key') }}</code>
                    </p>
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('firebase.enabled') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Firebase
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="Web"><i class="fa fa-circle text-warning"></i></span>
                        <span data-tippy-popover data-tippy-content="iOS"><i class="fa fa-circle text-danger"></i></span>
                        <span data-tippy-popover data-tippy-content="Backend"><i class="fa fa-circle text-info"></i></span>
                    </p>
                    <p>
                        Внутри есть firebaseConfig ->
                        <script>
                            // Your web app's Firebase configuration
                            var firebaseConfig = {
                                apiKey: "AIzaSyCCic4gdrx9DxaQtdEzI_eQTxPF49zarGc",
                                authDomain: "staymenity-database.firebaseapp.com",
                                databaseURL: "https://staymenity-database-default-rtdb.firebaseio.com",
                                projectId: "staymenity-database",
                                storageBucket: "staymenity-database.appspot.com",
                                messagingSenderId: "160811102397",
                                appId: "1:160811102397:web:6998c8d2d5729c123f47d4"
                            };
                        </script>
                    </p>
                    <p class="m-0 m-t-1">
                        Api Key: <code>{{ config('firebase.api_key') }}</code>
                    </p>
                    <p class="m-0 m-t-1">
                        Pair Key: <code>{{ config('firebase.pair_key') }}</code>
                    </p>
                    <p class="m-0 m-t-1">
                        Database url: <code>{{ config('firebase.projects.app.database.url') }}</code>
                    </p>
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('firebase.projects.app.credentials.file') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Firebase CREDENTIALS
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="Web"><i class="fa fa-circle text-warning"></i></span>
                        <span data-tippy-popover data-tippy-content="iOS"><i class="fa fa-circle text-danger"></i></span>
                        <span data-tippy-popover data-tippy-content="Backend"><i class="fa fa-circle text-info"></i></span>
                    </p>
                    <p class="m-0 m-t-1">
                        File: <a href="/{{ str_replace('public', '', config('firebase.projects.app.credentials.file')) }}">/{{ str_replace('public', '', config('firebase.projects.app.credentials.file')) }}</a>
                    </p>
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        <i class="fa fa-circle {{ config('nexmo.enabled') ? 'text-success' : 'text-danger' }}"></i>
                        &nbsp;
                        Nexmo KEY
                    </p>
                    <p class="m-0">
                        <span data-tippy-popover data-tippy-content="Backend"><i class="fa fa-circle text-info"></i></span>
                    </p>
                    <p class="m-0 m-t-1">
                        Key: <code>{{ config('nexmo.api_key') }}</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
