<div class="--comments-field-textarea">
    <form class="ajax-form" role="form" method="POST" action="{{ routeCmf('comment.action.item.post', ['id' => $oItem->id, 'name' => 'actionSaveComment']) }}"
          data-callback="commentsUpdateAfterSubmit"
    >
        <input type="hidden" name="commentable_type" value="{{ get_class($oItem) }}">
        @include('cmf.components.markdown.markdown', [
            'name' => 'comment',
            'oItem' => null,
            'title' => 'Комментарий',
        ])
        <button type="submit" class="btn btn-primary inner-form-submit" style="margin-top: 3px">Добавить</button>
    </form>
</div>
{{--<a href="#" class="btn btn-default btn-block btn-sm --comments-field-add">--}}
{{--    <i class="fa fa-plus" aria-hidden="true" style="color: #444"></i>--}}
{{--</a>--}}
{{--<a href="#" class="btn btn-default btn-block btn-sm --comments-field-close hidden">--}}
{{--    <i class="fa fa-times" aria-hidden="true" style="color: #444"></i>--}}
{{--</a>--}}
