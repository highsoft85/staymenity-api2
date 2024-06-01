<?php
$field = [
    \App\Cmf\Core\FieldParameter::TYPE => $oOption->type,
    \App\Cmf\Core\FieldParameter::TITLE => $oOption->title,
    \App\Cmf\Core\FieldParameter::PLACEHOLDER => !is_null($oOption->placeholder) ? $oOption->placeholder : $oOption->title,
    \App\Cmf\Core\FieldParameter::EMPTY => true,
    \App\Cmf\Core\FieldParameter::DEFAULT => isset($oValues[$oOption->name]) ? $oValues[$oOption->name]->value : '',
    'tooltip' => $oOption->tooltip,
    'title_caption' => $oOption->description,
];
if ($oOption->type === \App\Cmf\Core\MainController::DATA_TYPE_CHECKBOX) {
    $field['unchecked'] = true;
}
if ($oOption->type === \App\Cmf\Core\MainController::DATA_TYPE_SELECT) {
    $field['values'] = $oParameters->where('option_id', $oOption->id)->pluck('id', 'quantity')->flip()->toArray();
    $field['selected_values'] = [];
}
?>
<div class="col-12">
    @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.modals.tabs.parameters.' . $oOption->name))
        @include('cmf.content.' . $model . '.modals.tabs.parameters.' . $oOption->name, [
            'oOption' => $oOption,
            'name' => 'options[' . $oOption->name . ']',
            'field' => $field,
            'oItem' => $oItem,
        ])
    @else
        <div class="form-group">
            @include('cmf.content.default.form.default', [
                'name' => 'options[' . $oOption->name . ']',
                'field' => $field
            ])
        </div>
    @endif
</div>
