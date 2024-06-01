@hasSection('footer')
    @yield('footer')
@else
    <footer class="app-footer">
        {{ $oComposerSite->app->copyright ?? 'app.copyright' }}
        ©
        {{ now()->year }}
        {{-- {{ \Tremby\LaravelGitVersion\GitVersionHelper::getVersion() }}@include('git-version::version-comment') --}}
        <span class="float-right">
            Powered by
{{--            <b>{{ $oComposerSite->app->powered ?? 'app.powered' }}</b>--}}
            <a href="{{ $oComposerSite->app->powered['link'] ?? '/' }}" target="_blank">{{ $oComposerSite->app->powered['title'] ?? $oComposerSite->app->title }}</a>
        </span>
    </footer>
@endif

