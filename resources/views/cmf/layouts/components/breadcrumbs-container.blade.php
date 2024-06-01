<div style="position: relative;">
    @include('cmf.components.breadcrumbs')
    <div style="position: absolute;right: 5px;top: 5px;">
        @if(!isset($indexComponents) || (isset($indexComponents) && $indexComponents['create']))
            @hasSection('breadcrumb-right')
                @yield('breadcrumb-right')
            @endif
        @endif
    </div>
</div>
