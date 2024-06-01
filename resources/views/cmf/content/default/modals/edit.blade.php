<form class="row ajax-form"
      id="user-from"
      action="{{ routeCmf($model.'.update', ['id' => $oItem->id]) }}"
      data-counter=".admin-table-counter"
      data-list=".admin-table"
      data-list-action="{{ routeCmf($model.'.view.post') }}"
      data-callback="closeModalAfterSubmit, refreshAfterSubmit, editForm{{ ucfirst($model) }}"
>
    <input type="hidden" name="id" value="{{ $oItem->id }}">
    <div class="col-12">
        <?php
            $aGroups = [];
        ?>
        @foreach($fields as $name => $field)
            @if(isset($field['form']) && $field['form'] === false)

            @else
                @if(isset($field['group']) && !isset($aGroups[$field['group']]))
                    <div class="form-group is-row-{{ $field['group'] }}-group is-row-group row" style="margin-bottom: 0;">
                        @if(isset($field['group-title']))
                            <div class="form-group col-12" style="margin-bottom: 0;">
                                <b>{{ $field['group-title'] }}</b>
                                <hr style="margin: 5px 0;">
                            </div>
                        @endif
                        <?php
                            $aGroups[$field['group']] = collect($fields)->where('group', $field['group']);
                        ?>
                        @foreach($aGroups[$field['group']] as $groupName => $groupField)
                            <div class="form-group col-{{ $groupField['group-col'] }} is-{{ $groupName }}-group {{ isset($groupField['hidden']) && $groupField['hidden'] ? 'hidden' : '' }} {{ !empty($groupField['show_only']) ? 'is-disabled' : '' }}">
                                @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.modals.edit.' . $name))
                                    @include('cmf.content.' . $model . '.modals.edit.' . $name, [
                                        'name' => $groupName,
                                        'item' => $oItem,
                                        'field' => $groupField,
                                    ])
                                @else
                                    @include('cmf.content.default.form.default', [
                                        'name' => $groupName,
                                        'item' => $oItem,
                                        'field' => $groupField,
                                    ])
                                @endif
                            </div>
                        @endforeach
                    </div>
                @elseif(isset($field['group-hide']))
                @else
                    <div class="form-group is-{{ $name }}-group {{ isset($field['hidden']) && $field['hidden'] ? 'hidden' : '' }} {{ !empty($field['roles']) && !member()->hasAnyRole($field['roles']) ? 'is-disabled' : '' }} {{ !empty($field['show_only']) ? 'is-disabled' : '' }}">
                        @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.modals.edit.' . $name))
                            @include('cmf.content.' . $model . '.modals.edit.' . $name, [
                                'name' => $name,
                                'item' => $oItem,
                                'field' => $field,
                            ])
                        @else
                            @include('cmf.content.default.form.default', [
                                'name' => $name,
                                'item' => $oItem,
                                'field' => $field,
                            ])
                        @endif
                    </div>
                @endif
            @endif
        @endforeach
    </div>
    {{ $slot ?? '' }}
</form>
