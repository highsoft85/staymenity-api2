@foreach($search_fields as $name => $field)
    @switch($field['dataType'])
        @case(App\Cmf\Core\MainController::DATA_TYPE_TEXT)
        @case(App\Cmf\Core\MainController::DATA_TYPE_CHECKBOX)
        @case(App\Cmf\Core\MainController::DATA_TYPE_SELECT)
        <div class="col-2">
            @break
            @case(App\Cmf\Core\MainController::DATA_TYPE_DATE)
            <div class="col-4">
                @break
                @case(App\Cmf\Core\MainController::DATA_TYPE_NUMBER)
                <div class="col-1">
                    @break
                    @default
                    <div class="col-4">
                        @endswitch
                        <div class="form-group">
                            <label>{{ $field['title'] }}</label>

                            @if(\Illuminate\Support\Facades\View::exists('admin.content.' . $model . '.custom.search.' . $name))
                                @include('admin.content.' . $model . '.custom.search.' . $name)
                            @else
                                @switch($field['dataType'])
                                    @case(App\Cmf\Core\MainController::DATA_TYPE_TEXT)
                                    @case(App\Cmf\Core\MainController::DATA_TYPE_NUMBER)
                                    <input class="form-control ajax-input" data-form="#search-bar-form" type="text" name="{{ $name }}" value="{{ Request()->$name ?? '' }}" @if(!empty($field['mask'])) data-role="js-mask-{{ $field['mask'] }}@endif">
                                    @break
                                    @case(App\Cmf\Core\MainController::DATA_TYPE_CHECKBOX)
                                    <select class="form-control ajax-select" data-form="#search-bar-form" name="{{ $name }}">
                                        <option value="">Empty</option>
                                        <option value="0" {{ Request()->$name === '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ Request()->$name === '1' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @break
                                    @case(App\Cmf\Core\MainController::DATA_TYPE_SELECT)
                                    <select class="form-control ajax-select selectpicker {{ isset($field['relationship']) ? 'with-ajax' : '' }}" id="{{ $name }}_search_id"
                                            name="{{ $name }}{{ !empty($field['multiple']) ? '[]' : '' }}" data-form="#search-bar-form"
                                        {{ isset($field['relationship']) ? '
                                        data-live-search="true"
                                        data-abs-ajax-url='.routeCmf($model.'.action.item.post', ['id' => 0, 'name' => 'searchRelationshipField?field=' . $name . '']).'
                                        data-selected='.implode(',', $field['selected_values']) : ''}}
                                    >
                                        <option value="">Empty</option>
                                        @foreach($field['values'] as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ in_array($key, $field['selected_values']) ? 'selected' : '' }}
                                            >
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @break
                                    @case(App\Cmf\Core\MainController::DATA_TYPE_DATE)
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend bg-default">
                                            <span class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                                </div>
                                                <input type="text" id="{{ $name }}_id" class="form-control datetimepicker ajax-datetimepicker" name="{{ $name }}[begin]"
                                                       data-format="date"
                                                       data-role="js-mask-datetime"
                                                       data-form="#search-bar-form"
                                                       placeholder="От"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend bg-default">
                                            <span class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                                </div>
                                                <input type="text" id="{{ $name }}_id" class="form-control datetimepicker ajax-datetimepicker" name="{{ $name }}[end]"
                                                       data-format="date"
                                                       data-role="js-mask-datetime"
                                                       data-form="#search-bar-form"
                                                       placeholder="До"
                                                >
                                            </div>
                                        </div>
                                    </div>


                                    @break
                                @endswitch
                            @endif
                        </div>
                    </div>
        @endforeach
        @if(\Illuminate\Support\Facades\View::exists('admin.content.' . $model . '.components.search'))
            @include('admin.content.' . $model . '.components.search')
        @endif
