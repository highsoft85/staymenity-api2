@if(!is_null($oQuestion))
    <div class="row">
        <div class="col-12" style="display: flex;justify-content: space-between;">
            <div>
                {{ $oQuestion->question }}
            </div>
            <div>
                <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapseOne{{ $oQuestion->id }}" aria-expanded="true" aria-controls="collapseOne{{ $oQuestion->id }}">
                    <small>Редактировать</small>
                </button>
                <button class="btn btn-link btn-sm text-danger ajax-link" type="button"
                        action="{{ routeCmf($model.'.action.item.post', ['id' => $oQuestion->id, 'name' => 'actionQuestionDelete']) }}"
                        data-view=".--questions-form"
                        data-callback="updateView"
                        data-loading="1"
                >
                    <small>Удалить</small>
                </button>
            </div>
        </div>
    </div>
@else
{{--    <div class="row">--}}
{{--        <div class="col-12" style="display: flex;justify-content: space-between;">--}}
{{--            <div>--}}

{{--            </div>--}}
{{--            <div>--}}
{{--                <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapseOneTmp" aria-expanded="true" aria-controls="collapseOneTmp">--}}
{{--                    <small>Добавить</small>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endif
<div id="collapseOne{{ $oQuestion->id ?? 'Tmp' }}" class="row collapse {{ is_null($oQuestion) ? 'show' : '' }}">
    <div class="col-12 pt-1 pb-1">
        @include('cmf.content.default.modals.tabs.questions.item', [
            'oQuestion' => $oQuestion,
        ])
    </div>
    @if(is_null($oQuestion))
        <div class="col-12 mb-1">
            <button type="button" class="btn btn-primary ajax-link" data-form="#timeline-form">Сохранить</button>
        </div>
    @endif
</div>
