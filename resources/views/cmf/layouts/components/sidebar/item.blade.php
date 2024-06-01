@if(!isset($item['sub']))
    <li class="nav-item">
        <a class="nav-link is-{{ $key }}" href="/{{ config('cmf.prefix') !== '' ? config('cmf.prefix').'/' : '' }}{{ $key }}">
            <i class="{{ $item['iconCls'] }}"></i>
            {{ $item['title'] }}
            @if(isset($item['badge']))
                <span class="badge badge-{{ $item['badge']['type'] }}">{{ $item['badge']['title'] }}</span>
            @endif
        </a>
    </li>
@else
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="{{ $item['iconCls'] }}"></i>
            {{ $item['title'] }}
            @if(isset($item['badge']))
                <span class="badge badge-{{ $item['badge']['type'] }}">{{ $item['badge']['title'] }}</span>
            @endif
        </a>
        <ul class="nav-dropdown-items">
            @foreach($item['sub'] as $sKey => $sub)
                <li class="nav-item">
                    <a class="nav-link is-{{ $sKey }}" href="/{{ config('cmf.prefix') !== '' ? config('cmf.prefix').'/' : '' }}{{ $sKey }}">
                        <i class="{{ $sub['iconCls'] }}"></i>
                        @if(isset($sub['subtitle']))
                            <span class="__title-subtitle">
                                <span class="__subtitle">{{ $sub['subtitle'] }}</span>
                                <span class="__title">{{ $sub['title'] }}</span>
                            </span>
                        @else
                            {{ $sub['title'] }}
                        @endif
                        @if(isset($sub['badge']))
                            <span class="badge badge-{{ $sub['badge']['type'] }}">{{ $sub['badge']['title'] }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </li>
@endif
