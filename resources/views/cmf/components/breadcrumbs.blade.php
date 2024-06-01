{{--@if($sComposerRouteView && Session::exists($sComposerRouteView) && Session::has($sComposerRouteView.'.count'))--}}
{{--    <div style="position: absolute;top: 46px;right: 35px;" class="--count-table-view font-weight-bold text-black">--}}
{{--        <span class="--get">{{ Session::get($sComposerRouteView.'.count.get') }}</span>&nbsp;/&nbsp;<span class="--all">{{ Session::get($sComposerRouteView.'.count.total') }}</span>--}}
{{--    </div>--}}
{{--@endif--}}
@hasSection('breadcrumb')
    @yield('breadcrumb')
@else
    @if(isset($breadcrumb) && !empty($breadcrumb))
        @if(isset($oItem) && !empty($oItem))
            {{ \DJStarCOM\Breadcrumbs\Facades\Breadcrumbs::render($breadcrumb . '.item', $oItem) }}
        @else
            {{ \DJStarCOM\Breadcrumbs\Facades\Breadcrumbs::render($breadcrumb) }}
        @endif
    @else
        @if(isset($oItem) && !empty($oItem))
            @if(isset($breadcrumbPage) && !empty($breadcrumbPage))
                {{ \DJStarCOM\Breadcrumbs\Facades\Breadcrumbs::render($sComposerRouteView . '.item.page', $oItem, $breadcrumbPage) }}
            @else
                {{ \DJStarCOM\Breadcrumbs\Facades\Breadcrumbs::render($sComposerRouteView . '.item', $oItem) }}
            @endif
        @else
            @if(isset($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_BREADCRUMBS]) && !is_null($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_BREADCRUMBS]))
                {{ \DJStarCOM\Breadcrumbs\Facades\Breadcrumbs::render($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_BREADCRUMBS], null) }}
            @else
                {{ \DJStarCOM\Breadcrumbs\Facades\Breadcrumbs::render($sComposerRouteView, null) }}
            @endif
        @endif
    @endif

@endif
