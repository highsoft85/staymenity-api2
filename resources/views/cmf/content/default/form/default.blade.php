
    @if(!in_array($field['dataType'], [
            App\Cmf\Core\MainController::DATA_TYPE_CHECKBOX,
            App\Cmf\Core\MainController::DATA_TYPE_MARKDOWN,
        ])
        && empty($no_title)
        && (!isset($field['showEdit']) || $field['showEdit'])
        && !isset($field['hideLabel'])
    )
        <label>
            {!! $field['title'] !!}{!! !empty($field['required']) ? '<i class="r">*</i>' : '' !!}{!! isset($field['title_caption']) && !is_null($field['title_caption']) ? '<span class="text-muted"> - ' . $field['title_caption'] . '</span>' : '' !!}
            @if(isset($field['tooltip']))
                @include('cmf.components.tooltip.question', [
                    'title' => $field['tooltip'],
                ])
            @endif
        </label>
    @endif

    @if(View::exists('cmf.content.'.$model.'.form.field.'.$name) && !isset($dontInclude))
        @include('cmf.content.'.$model.'.form.field.'.$name, [
            'oItem' => $item,
            'field' => $field,
        ])
    @else
        @switch($field['dataType'])
            @case(App\Cmf\Core\MainController::DATA_TYPE_NUMBER)
            <input type="text" class="form-control" name="{{ $name }}" placeholder="{{ $field['placeholder'] ?? $field['title'] }}" data-role="js-mask-int" data-length="{{ $field['length'] ?? 2 }}"
                   value="{{ $item->$name ?? $field['default'] ?? '' }}" {{ !empty($field['required']) ? 'required' : '' }} {{ !empty($field['disabled']) ? 'disabled' : '' }}>
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_TEXT)
                <input type="text" class="form-control" name="{{ $name }}" placeholder="{{ $field['placeholder'] ?? $field['title'] }}" id="{{ $name }}_id"
                {{ !empty($field['readOnly']) ? 'readonly' : '' }}
                {{ !empty($field['disabled']) ? 'disabled' : '' }}
                value="{{ $item->$name ?? $field['default'] ?? '' }}" {{ !empty($field['required']) ? 'required' : '' }}
                {{ isset($field['mask_phone']) ? 'data-role=js-mask-phone' : '' }}
                {{ isset($field[\App\Cmf\Core\FieldParameter::MASK_INSTAGRAM]) ? 'data-role=js-mask-instagram' : '' }}

{{--                {{ $name === 'phone' ? 'data-role=js-mask-phone' : '' }}--}}
                >
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_SELECT)
            <?php
                $fieldName = isset($fieldName) ? $fieldName : $name;
                $fieldModel = isset($fieldModel) ? $fieldModel : $model;
            ?>
                <select class="form-control selectpicker {{ isset($field['relationship']) ? 'with-ajax' : '' }}" id="{{ $name }}_id"
                        name="{{ $name }}{{ !empty($field['multiple']) ? '[]' : '' }}"
                        {{ !empty($field['required']) ? 'required' : '' }}
                        {{ !empty($field['attributes']) ? implode(' ', $field['attributes']) : '' }}
                        {{ !empty($field['multiple']) ? 'multiple' : '' }}
                        {{ isset($field['relationship']) ? '
                        data-live-search="true"

                        data-abs-ajax-url='.routeCmf($fieldModel.'.action.item.post', ['id' => 0, 'name' => 'searchRelationshipField?field=' . $fieldName . '']).'
                        data-selected='.implode(',', $field['selected_values']) : ''}}
                >
                    @if(!empty($field['empty']))
                        <option value="">Empty</option>
                    @endif
                    @foreach($field['values'] as $key => $value)
                        <option value="{{ $key }}"
                            {{ in_array($key, $field['selected_values']) ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_CHECKBOX)
                <div class="abc-checkbox">
                    <input type="checkbox" id="checkbox-{{ $name }}" class="styled" name="{{ $name }}" value="1" id="{{ $name }}_id"
                            {{ isset($item) && $item->$name ? 'checked' : '' }}
                            {{ !isset($item) && !empty($field['default']) ? 'checked' : '' }}
                            {{ !empty($field['required']) ? 'required' : '' }}
                            {{ !empty($field['unchecked']) ? 'data-unchecked' : '' }}
                    >
                    <label for="checkbox-{{ $name }}">{{ $field['title_form'] ?? $field['title'] }}{!! !empty($field['required']) ? '<i class="r">*</i>' : '' !!}</label>
                    @if(isset($field['tooltip']))
                        @include('cmf.components.tooltip.question', [
                            'title' => $field['tooltip'],
                        ])
                    @endif
                </div>
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_DATE)
                <div class="input-group">
                    @if(!isset($field['noIcon']))
                        <div class="input-group-prepend bg-default">
                            <span class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    @endif
                    @if(isset($field['split']) && $field['split'])
                        <input type="text" id="{{ $name }}_id" class="form-control datetimepicker" name="{{ $name }}_split[date]"
                               {{ !empty($field['required']) ? 'required' : '' }}
                               data-format="date"
                               data-role="js-mask-datetime"
                               value="{{ isset($item) && !is_null($item->$name) ? $item->$name->format('d.m.Y') : $field['default']['date'] ?? '' }}"
                               placeholder="Дата"
                        >
                        <input type="text" id="{{ $name }}_id" class="form-control datetimepicker" name="{{ $name }}_split[time]"
                               data-format="time"
                               data-role="js-mask-datetime"
                               value="{{ isset($item) && !is_null($item->$name) && $item->$name->format('H:i') !== '00:00' ? $item->$name->format('H:i') : $field['default']['time'] ?? '' }}"
                               placeholder="00:00"
                               style="margin-left: -1px;"
                        >
                    @else
                        <input type="text" id="{{ $name }}_id" class="form-control datetimepicker" name="{{ $name }}"
                               {{ !empty($field['required']) ? 'required' : '' }}
                               @if(isset($field['parent']))
                               data-parent="{{ $field['parent'] }}"
                               @endif
                               @if(isset($field['datetime']) && $field['datetime'])
                               data-format="datetime"
                               data-role="js-mask-datetime"
                               value="{{ isset($item) && !is_null($item->$name) ? $item->$name->format('m/d/Y H:i') : $field['default'] ?? '' }}"
                               @elseif(isset($field['time']) && $field['time'])
                               data-format="time"
                               data-role="js-mask-time"
                               value="{{ isset($item) && !is_null($item->$name) ? $item->$name->format('H:i') : $field['default'] ?? '' }}"
                               @else
                               data-format="date"
                               data-role="js-mask-datetime"
                               value="{{ isset($item) && !is_null($item->$name) ? $item->$name->format('d.m.Y') : $field['default'] ?? '' }}"
                               @endif
                               placeholder="{{ $field['placeholder'] ?? $field['title'] }}"
                        >
                    @endif
                </div>
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_TEXTAREA)
                {{-- &#10; --}}
                <textarea class="form-control" name="{{ $name }}" cols="15" rows="{{ $field['rows'] ?? 5 }}" placeholder="{!! $field['placeholder'] ?? $field['title'] !!}" id="{{ $id ?? $name }}_id"
                        {{ !empty($field['required']) ? 'required' : '' }}
                >{{ $item->$name ?? $field['default'] ?? '' }}</textarea>
                @if(isset($field['limit']))
                    <div class="textarea-limit text-limit-counter" data-target="#{{ $id ?? $name }}_id">
                        <span class="current">0</span>/<span class="limit">{{ $field['limit'] }}</span>
                    </div>
                @endif
{{--                <div class="markdown-container">--}}
{{--                    <a class="markdown-toggler btn btn-sm text-black" title="Предпросмотр">--}}
{{--                        <i class="fa fa-eye"></i>--}}
{{--                    </a>--}}
{{--                    <div class="markdown-window hidden">--}}
{{--                        <span class="badge badge-default">Предпросмотр</span>--}}
{{--                        <div class="markdown-preview" id="{{ $name }}_id-markdown-preview"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_IMG)
            @case(App\Cmf\Core\MainController::DATA_TYPE_FILE)
                <input id="{{ $name }}-file-id" type="file" class="form-control" name="{{ $name }}" placeholder="{{ $field['title'] }}"
                value="{{ $item->$name ?? $field['default'] ?? '' }}"
                    {{ !empty($field['required']) ? 'required' : '' }}
                    {{ !empty($field['accept']) ? 'accept=' . $field['accept'] . '': '' }}
                >
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_CUSTOM)
                @if(View::exists('cmf.content.'.$model.'.form.field.'.$name))
                    @include('cmf.content.'.$model.'.form.field.'.$name, [
                        'oItem' => $item
                    ])
                @endif
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_RADIO)
                <div class="abc-radio">
                    @foreach($field[\App\Cmf\Core\FieldParameter::RADIO_VALUES] as $id => $title)
                        <div class="radio">
                            <input type="radio" name="{{ $name }}" id="radio_{{ $model }}_{{ $name }}_{{ $id }}" value="{{ $id }}"
                                   @if(!is_null($item))
                                       {{ $item->$name === $id ? 'checked="checked"' : '' }}
                                   @endif
                                @if(isset($field['default']))
                                    {{ $field['default'] === $id ? 'checked="checked"' : '' }}
                                @endif
                            >
                            <label for="radio_{{ $model }}_{{ $name }}_{{ $id }}">
                                {{ $title }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_COLOR)
            <input type="text" class="form-control color-input" name="{{ $name }}" placeholder="{{ $field['title'] }}" value="{{ $item->$name ?? '' }}"
                {{ !empty($field['required']) ? 'required' : '' }}
            >
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_MARKDOWN)
                @include('cmf.components.markdown.markdown', [
                    'oItem' => $item ?? null,
                    'field' => $name,
                    'title' => $field['title'],
                    'placeholder' => $field['placeholder'] ?? $field['title'],
                    'default' => $field['default'] ?? '',
                    'rows' => $field['rows'] ?? 5,
                ])
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_MARKDOWN_IMAGE)
                @include('cmf.components.markdown.markdown', [
                    'oItem' => $item,
                    'field' => $name,
                    'title' => $field['title'],
                    'placeholder' => $field['placeholder'] ?? $field['title'],
                    'default' => $field['default'] ?? '',
                    'rows' => $field['rows'] ?? 5,
                    'imageable' => true,
                ])
            @break
        @endswitch
    @endif
