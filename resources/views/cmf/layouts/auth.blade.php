<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <link rel="icon" href="{{ asset(config('cmf.favicon')) }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('cmf.name') }}</title>
    <meta name="description" content="{{ $oComposerSite->app->description ?? 'app.description'}}">
    <meta name="keywords" content="{{ $oComposerSite->app->keywords ?? 'app.keywords'}}">
    <link rel="stylesheet" href="{{ asset('cmf/css/cmf.css') }}?v={{ $sComposerVersion ?? '' }}">
    @stack('styles')
</head>
<body class="app flex-row align-items-center">
@hasSection('body')
    @yield('body')
@else
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @yield('content')
            </div>
        </div>
    </div>
@endif

<div id="modals">
    @yield('modals')
</div>

<div id="scripts">
    @include('cmf.components.toastr')
    <script>
        window.api_url = '{{ config('api.url') }}';
    </script>
    <script src="{{ asset('cmf/js/cmf.js') }}?v={{ $sComposerVersion ?? '' }}"></script>
    @stack('scripts')
</div>
</body>
</html>
