<div class="sidebar">
    <div class="sidebar-header">
        @include('cmf.layouts.components.sidebar.user')
    </div>
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">
                Menu
            </li>
            @foreach(config('cmf.sidebar.admin') as $key => $item)
                @if(isset($item['divider']))
                    <li class="divider"></li>
                    <li class="nav-title">
                        {{ $item['divider'] }}
                    </li>
                @endif
                @if(isset($item['roles']))
                    @if(member()->hasAnyRole($item['roles']))
                        @include('cmf.layouts.components.sidebar.item', [
                           'item' => $item,
                           'key' => $key,
                        ])
                    @endif
                @else
                    @include('cmf.layouts.components.sidebar.item', [
                       'item' => $item,
                       'key' => $key,
                    ])
                @endif
            @endforeach
            @yield('menu')
        </ul>
    </nav>
</div>
