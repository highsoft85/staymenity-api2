
@php
    $url = explode('/', stristr(URL::current(), $sComposerRouteView));
    $menu = config('cmf.sidebar');
@endphp
@foreach($url as $key => $value)
    @if(isset($url[$key+1]))
        <li class="breadcrumb-item">
            @if($value === $sComposerRouteView)
                <a href="/admin">
                    {{ $menu['/']['title'] }}
                </a>
            @else
                @if(isset($menu[$value]['title']))
                    <a href="/{{ $sComposerRouteView }}/{{ $value }}">
                        {{ $menu[$value]['title'] }}
                    </a>
                @else
                    @if(isset($menu[$url[$key-1]]) && isset($menu[$url[$key-1]]['sub'][$value]))
                        @if(isset($menu[$url[$key-1]]['sub'][$value]['hidden']))
                            {{ $menu[$url[$key-1]]['sub'][$value]['title'] }}
                        @else
                            <a href="/{{ $sComposerRouteView }}/{{ $url[$key-1] }}/{{ $value }}">
                                {{ $menu[$url[$key-1]]['sub'][$value]['title'] }}
                            </a>
                        @endif
                    @else
                        @if(isset($url[$key-2]))
                            @if($value === 'edit')
                                {{ $value }}
                            @else
                                <a href="/{{ $sComposerRouteView }}/{{ $url[$key-2] }}/{{ $url[$key-1] }}/{{ $value }}">
                                    {{ $value }}
                                </a>
                            @endif
                        @else
                            <a href="/{{ $sComposerRouteView }}/{{ $url[$key-1] }}/{{ $value }}">
                                {{ $value }}
                            </a>
                        @endif
                    @endif
                @endif
            @endif
        </li>
    @else
        @if($key === 2)
            <li class="breadcrumb-item active">
                @if(isset($menu[$url[$key-1]]) && isset($menu[$url[$key-1]]['sub'][$value]))
                    {{ $menu[$url[$key-1]]['sub'][$value]['title'] }}
                @else
                    {{ $value }}
                @endif
            </li>
        @else
            <li class="breadcrumb-item active">
                @if($value === $sComposerRouteView)
                    {{ $menu['/']['title'] }}
                @else
                    @if(isset($menu[$value]['title']))
                        {{ $menu[$value]['title'] }}
                    @else
                        {{ $value }}
                    @endif
                @endif
            </li>
        @endif
    @endif
@endforeach

<li class="breadcrumb-menu" style="top: 2px;">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        {{--
        <a class="btn btn-secondary" href="#"><i class="icon-speech"></i></a>
        <a class="btn btn-secondary" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a>

        <div class="btn-group" role="group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-settings"></i> &nbsp;Settings
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                    <strong>Account</strong>
                </div>

                <a class="dropdown-item" href="#"><i class="fa fa-tasks"></i> Tasks<span class="badge badge-danger">13</span></a>
                <a class="dropdown-item" href="#"><i class="fa fa-comments"></i> Comments<span class="badge badge-warning">234</span></a>

                <div class="dropdown-header text-center">
                    <strong>Settings</strong>
                </div>

                <a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> Settings</a>
                <a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="badge badge-default">75</span></a>
                <a class="dropdown-item" href="#"><i class="fa fa-file"></i> Projects<span class="badge badge-primary">2</span></a>

            </div>
        </div>
        --}}
    </div>
</li>
