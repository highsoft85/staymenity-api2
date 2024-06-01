<?php
$model = isset($model) ? $model : $model;
?>
@if(empty($hide_title))
    <div class="col-md-3 col-sm-12 mbt-5">
        <b>{{ $value['title'] }}</b>
    </div>
@endif
<div class="col-md-9 col-sm-12 mbt-5">
    @if(View::exists('cmf.content.'.$model.'.modals.field.'.$name))
        @include('cmf.content.'.$model.'.modals.field.'.$name, [
            'oItem' => $item
        ])
    @elseif(View::exists('cmf.content.'.$model.'.table.column.'.$name))
        @include('cmf.content.'.$model.'.table.column.'.$name, [
            'oItem' => $item
        ])
    @elseif(View::exists('cmf.content.default.table.column.'.$name))
        @include('cmf.content.default.table.column.'.$name, [
            'oItem' => $item
        ])
    @else
        @switch($value['dataType'])
            @case(App\Cmf\Core\MainController::DATA_TYPE_NUMBER)
            @case(App\Cmf\Core\MainController::DATA_TYPE_TEXT)
            {{ $item->$name }}
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_IMG)
            <img src="{{ Storage::url($item->$name) }}"/>
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_SELECT)
            @if((!empty($value['multiple']) && !count($item->$name)) || (empty($item->$name) && $item->$name !== 0))
                Empty
                @break
            @endif
            @if(!empty($hide_title))
                @if(!empty($value['relationship']))
                    @component($theme.'.content.default.modals.a_href', [
                        'action' => routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'getRelationshipFieldModal?field=' . $name]),
                        'class' => 'btn btn-dark btn-sm btn-block',
                        'ajax_init' => 'tooltip, tableShowOnly',
                        'table_hide' => '2',
                        'tippy_popover' => '',
                        'tippy_content' => "Show",
                    ])
                        {{--                <i class="fa fa-eye" aria-hidden="true"></i>--}}
                        <span>
                            @if(!empty($value['multiple']))
                                {{ $oItem->$name->count() }}
                            @else
                                @if(!empty($value['order']))
                                    @if(!empty($value['order']['by']))
                                        @php $field_name = $value['order']['by'] @endphp
                                    @endif
                                @endif
                                @if(!empty($field_name))
                                    {{ $oItem->$name->$field_name }}
                                @elseif(!empty($value['alias']))
                                    @if(!empty($value['limit']))
                                        {{ \Illuminate\Support\Str::limit($oItem->$name->{$value['alias']}, $value['limit']) }}
                                    @else
                                        {{ $oItem->$name->{$value['alias']} }}
                                    @endif
                                @else
                                    @if(!empty($value['limit']))
                                        {{ \Illuminate\Support\Str::limit($oItem->$name->name, $value['limit']) }}
                                    @else
                                        {{ $oItem->$name->name }}
                                    @endif
                                @endif
                            @endif
                        </span>
                    @endcomponent
                @else
                    @if($name === 'status')
                        @include('cmf.content.default.table.column.status', [
                            'oItem' => $item
                        ])
                    @else
                        {{ $value['values'][$oItem->$name] }}
                    @endif
                @endif
            @else
                @if(!empty($value['multiple']))
                    <ul class="list-group list-group-show-sm list-group-flush">
                        @endif
                        @foreach($value['values'] as $key => $value_item)
                            @if(isset($item) && !is_null($item->$name))
                                @if(in_array($key, $value['selected_values']))
                                    @if(!empty($value['multiple']))
                                        <li class="list-group-item">
                                            @endif
                                            @if(is_numeric($key) && !empty($value['relationship']))
                                                @component($theme.'.content.default.modals.a_href', [
                                                    'action' => routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'getRelationshipFieldModal?field=' . $name . '&key=' . $key]),
                                                ])
                                                    @if(is_array($value_item) && isset($value_item['value']) && isset($value_item['description']))
                                                        {{ $value_item['value'] }} - {{ $value_item['description'] }}
                                                    @else
                                                        {{ $value_item }}
                                                    @endif
                                                @endcomponent
                                            @else
                                                @if(is_array($value_item) && isset($value_item['value']) && isset($value_item['description']))
                                                    {{ $value_item['value'] }} - {{ $value_item['description'] }}
                                                @else
                                                    {{ $value_item }}
                                                @endif
                                            @endif
                                            @if(!empty($value['multiple']))
                                        </li>
                                    @endif
                                @endif
                            @endif
                        @endforeach
                        @if(!empty($value['multiple']))
                    </ul>
                @endif
            @endif
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_CHECKBOX)
            @if(View::exists('cmf.content.default.table.column.'.$name))
                @include('cmf.content.default.table.column.'.$name, [
                    'oItem' => $item
                ])
            @else
                @if(isset($item) && $item->$name)
                    <div class="text-success">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                @else
                    <div class="text-danger">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </div>
                @endif
            @endif
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_DATE)
                @if(isset($item) && !is_null($item->$name))
                    @if(isset($value['format']) && $value['format'])
                        {{ $item->$name->format($value['format']) }}
                    @elseif(isset($value['datetime']) && $value['datetime'])
                        {{ $item->$name->format('Y-m-d H:i:s') }}
                    @else
                        {{ $item->$name->format('Y-m-d') }}
                    @endif
                @else
                    <span class="text-opacity">-</span>
                @endif
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_TEXTAREA)
            <div class="markdown-view">{{ $item->$name }}</div>
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_JSON)
            @foreach(json_decode($item->$name) as $key => $value)
                <b>{{ $key }}:</b> {{ $value }} <br>
            @endforeach
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_FILE)

            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_RADIO)
                @if(isset($item) && !is_null($item->$name) && isset($value[\App\Cmf\Core\FieldParameter::RADIO_VALUES]))
                    {{ $value[\App\Cmf\Core\FieldParameter::RADIO_VALUES][$item->$name] }}
                @endif
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_COLOR)
            <div class="d-flex justify-content-start align-items-center">
                <div style="background-color: {{ $item->$name }}; width: 100px;height: 19px;margin: 5px 0;display: inline-block"></div>
                <div style="margin: 5px;">{{ $item->$name }}</div>
            </div>
            @break
        @endswitch
    @endif
    @if(isset($tooltip) && !is_null($tooltip))
        @include('cmf.components.tooltip.question', [
            'title' => $tooltip['title'],
            'type' => $tooltip['type'] ?? 'default',
            'icon' => $tooltip['icon'] ?? 'question',
        ])
    @endif
</div>
