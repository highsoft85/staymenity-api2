<?php
$model = isset($model) ? $model : $model;
?>
@if($value['dataType'] != App\Cmf\Core\MainController::DATA_TYPE_CUSTOM && empty($hide_title))
    <td>
        <b>{{ $value['title'] }}</b>
    </td>
@endif
<td>
    @if(View::exists('cmf.content.'.$model.'.table.column.'.$name))
        @include('cmf.content.'.$model.'.table.column.'.$name, [
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
                        'tippy_content' => "Посмотреть",
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
                                {{ empty($field_name) ? $oItem->$name->name : $oItem->$name->$field_name }}
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
                    <ul class="list-group list-group-show-sm">
                        @endif
                        @foreach($value['values'] as $key => $value_item)
                            @if(isset($item) && !is_null($item->$name))
                                @if(in_array($key, $value['selected_values']))
                                    @if(!empty($value['multiple']))
                                        <li class="list-group-item">
                                            @endif
                                            @if(is_numeric($key) && !is_string($value_item))
                                                @component($theme.'.content.components.modals.a_href', [
                                                    'action' => routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'getRelationshipFieldModal?field=' . $name . '&key=' . $key]),
                                                ])
                                                    {{ $value_item }}
                                                @endcomponent
                                            @else
                                                {{ $value_item }}
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
                    <span class="badge badge-success">Yes</span>
                @else
                    <span class="badge badge-danger">No</span>
                @endif
            @endif
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_DATE)
            @if(isset($item) && !is_null($item->$name))
                @if(isset($value['datetime']) && $value['datetime'])
                    {{ isset($value['seconds']) ? $item->$name->format('d.m.Y H:i:s') : $item->$name->format('d.m.Y H:i') }}
                @else
                    {{ $item->$name->format('d.m.Y') }}
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
            @case(App\Cmf\Core\MainController::DATA_TYPE_COLOR)
            <div style="background-color: {{ $item->$name }}; width: 100%;height: 19px;margin: 5px 0;"></div>
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
</td>
