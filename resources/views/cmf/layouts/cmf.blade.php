<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <link rel="icon" href="{{ asset(config('cmf.favicon')) }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('cmf.name') }}</title>
    <meta name="description" content="{{ $oComposerSite->app->description ?? 'app.description' }}">
    <meta name="keywords" content="{{ $oComposerSite->app->keywords ?? 'app.keywords' }}">
    <link rel="stylesheet" href="{{ asset('cmf/css/cmf.css') }}?v={{ $sComposerVersion ?? '' }}">
    @if(!\Illuminate\Support\Facades\Auth::guest())
        <script>
            window.user = @json([
                'id' => \Illuminate\Support\Facades\Auth::user()->id
            ]);
        </script>
    @endif
    @stack('styles')
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden {{ $sBodyClass ?? '' }} {{ \Illuminate\Support\Facades\Session::has('sidebar-toggle') ? 'sidebar-hidden' : '' }}">
@hasSection('body')
    @yield('body')
@else
    <div id="header">
        @include('cmf.layouts.components.header')
    </div>

    <div id="app" class="app-body"
{{--         style="min-height: 100vh;margin-top: 55px;min-width: 1150px;"--}}
    >

        @include('cmf.layouts.components.sidebar')

        <main class="main">
            @include('cmf.layouts.components.breadcrumbs-container')

            <div class="container-fluid">
                @if(!isset($indexComponents) || (isset($indexComponents) && $indexComponents['search']))
                    @hasSection('breadcrumb-search')
                        @yield('breadcrumb-search')
                    @endif
                @endif

                <div class="animated fadeIn">
                    @yield('content')
                </div>
            </div>
        </main>
        @include('cmf.layouts.components.aside')
    </div>

    <div id="footer">
        @include('cmf.layouts.components.footer')
    </div>
@endif

<div id="modals">
    @include('cmf.components.dialogs.settings.command')
    @include('cmf.components.dialogs.ajax')
    @include('cmf.components.dialogs.confirm')
    @include('cmf.components.dialogs.info')
    @include('cmf.components.dialogs.bar')

    @yield('modals')
</div>
<div id="scripts">
    @stack('scripts-before')
    @include('cmf.components.toastr')
    @if(config('services.yandex_map.enabled'))
        <script src="https://api-maps.yandex.ru/2.1/?lang=en_RU&amp;apikey={{ config('services.yandex_map.key') }}" type="text/javascript"></script>
    @endif
    <script src="{{ asset('cmf/js/cmf.js') }}?v={{ $sComposerVersion ?? '' }}"></script>
    @stack('scripts')
</div>
</body>
</html>
