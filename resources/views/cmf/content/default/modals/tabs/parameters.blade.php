<form class="row ajax-form"
      action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionUpdateParameters']) }}"
      data-callback="editForm{{ ucfirst($model) }}"
>
    @if(isset($alert))
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                {!! $alert !!}
            </div>
        </div>
    @endif
{{--    @foreach($oOptions as $oOption)--}}
{{--        @include('cmf.content.default.modals.tabs.parameters.field_parameter', [--}}
{{--            'oOptions' => $oOption,--}}
{{--            'oValues' => $oValues,--}}
{{--            'oParameters' => $oParameters->where('option_id', $oOption->id),--}}
{{--        ])--}}
{{--    @endforeach--}}
    @include('cmf.content.default.modals.tabs.parameters.field_parameter', [
        'oOptions' => $oOptions,
        'oValues' => $oValues,
        'oParameters' => $oParameters,
    ])
</form>
