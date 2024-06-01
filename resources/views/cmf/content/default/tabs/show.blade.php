<ul class="nav nav-tabs" role="tablist">
@foreach($field_tabs as $key => $field_tab)
    <li class="nav-item {{ isset($field_tab['hidden']) && $field_tab['hidden'] ? 'hidden' : '' }}">
        <a style="white-space: nowrap" class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab" href="#{{ str_replace('.', '_', $key) }}-tab-{{ $loop->index }}"
           role="tab"
        @if(!empty($field_tab['tabs_attributes']))
            @foreach($field_tab['tabs_attributes'] as $attribute => $value)
                {{ $attribute }} = "{{ $value }}"
            @endforeach
        @endif
        >{{ $field_tab['title'] }}</a>
    </li>
@endforeach
</ul>
<div class="tab-content">
    @foreach($field_tabs as $key => $field_tab)
        <div class="tab-pane tab-submit {{ $loop->first ? 'active' : '' }}" id="{{ str_replace('.', '_', $key) }}-tab-{{ $loop->index }}"
             role="tabpanel"
        @if(!empty($field_tab['content_attributes']))
            @foreach($field_tab['content_attributes'] as $attribute => $value)
                {{ $attribute }} = "{{ $value }}"
            @endforeach
        @endif
        >
            @if(isset($field_tab['sub_tabs']))
                @include('cmf.content.default.tabs', [
                    'field_tabs' => $field_tab['sub_tabs']
                ])
            @else
                @if(!empty($field_tab['fields']))
                    @include('cmf.content.default.modals.' . $type, [
                        'num' => $loop->index,
                        'onlyFields' => $field_tab['fields'],
                        'type' => $type
                    ])
                @else
                    @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.modals.' . $key))
                        @include('cmf.content.' . $model . '.modals.' . $key)
                    @else
                        @include('cmf.content.default.modals.show')
                    @endif
                @endif
            @endif
        </div>
    @endforeach
</div>

