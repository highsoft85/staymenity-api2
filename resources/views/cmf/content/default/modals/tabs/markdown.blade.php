<form class="ajax-form"
      action="{{ routeCmf($model.'.text.save.post', ['id' => $oItem->id]) }}"
      data-callback="afterSaveMarkdown"
>
    <input type="hidden" name="id" value="{{ $oItem->id }}">
    @include('cmf.components.markdown.markdown', [
        'oItem' => $oItem,
        'name' => 'description',
        'title' => '',
        'placeholder' => 'Текст',
        'rows' => 30,
        'imageable' => isTextImageable($model),
        'textable' => true,
    ])
</form>


