<form class="ajax-form"
      action="{{ routeCmf($sComposerRouteView.'.text.save.post', ['id' => $oItem->id]) }}"
      data-list=".admin-table"
      data-list-action="{{ routeCmf($sComposerRouteView.'.view.post') }}"
      data-form-data=".pagination-form"
      data-callback="refreshAfterSubmit, updateSummernoteAfterSubmit"
      data-before-data="updateSummernoteBeforeSubmit"
>
    <input type="hidden" name="id" value="{{ $oItem->id }}">
    @include('cmf.components.summernote', [
        'url' => routeCmf($sComposerRouteView.'.text.save.post', ['id' => $oItem->id]),//url('/admin/posts/action/upload-wysiwyg'),
        'uploadUrl' => routeCmf($sComposerRouteView.'.text.upload.post', ['id' => $oItem->id]),//url('/admin/posts/action/upload-wysiwyg'),
        'model' => $sComposerRouteView,
        'oItem' => $oItem,
        'id' => 'summernote-'.$oItem->id.'-main',
        'itemText' => $itemText,
    ])
</form>
