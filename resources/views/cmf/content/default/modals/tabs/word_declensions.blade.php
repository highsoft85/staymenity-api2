<form class="row ajax-form --tab-word-declension-form" action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionWordDeclensionsSave']) }}">
{{--    <div class="col-12">--}}
{{--        <button role="button" class="btn btn-primary ajax-link mb-1"--}}
{{--                action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionWordDeclensionsAutoGenerate']) }}"--}}
{{--                data-view=".--tab-word-declension-form"--}}
{{--                data-callback="replaceView"--}}
{{--                data-loading="1"--}}
{{--        >--}}
{{--            Сгенерировать--}}
{{--        </button>--}}
{{--    </div>--}}

    @foreach((new \App\Models\WordDeclension())->getTypes() as $name => $title)
        <div class="col-12">
            <div class="form-group">
                @include('cmf.content.default.form.default', [
                    'name' => 'word_declension[' . $name . ']',
                    'field' => [
                        \App\Cmf\Core\FieldParameter::TYPE => \App\Cmf\Core\MainController::DATA_TYPE_TEXT,
                        \App\Cmf\Core\FieldParameter::TITLE => $title,
                        \App\Cmf\Core\FieldParameter::DEFAULT => !is_null($oItem->getDeclensionItemByType($name)) ? $oItem->getDeclensionItemByType($name)->value : '',
                    ]
                ])
                @if($name === \App\Models\WordDeclension::TYPE_WHERE)
                    <span class="help-block text-muted">Используется в генерировании мета тегов для тура</span>
                @endif
            </div>
        </div>
    @endforeach
</form>
