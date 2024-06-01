<form class="row ajax-form --questions-form"
      action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionQuestionSave']) }}"
      data-view=".--questions-form"
      data-callback="replaceView"
>
    <div class="col-12">
        @foreach($oItem->questions()->ordered()->get() as $oQuestion)
            @include('cmf.content.default.modals.tabs.questions.group-item', [
                'oQuestion' => $oQuestion,
            ])
        @endforeach
    </div>
    <div class="col-12 pt-1">
        <div class="card">
            <div class="card-header">
                 Новый вопрос
            </div>
            <div class="card-body p-1">
                @include('cmf.content.default.modals.tabs.questions.item', [
                    'oQuestion' => null,
                ])
            </div>
        </div>
    </div>
</form>

