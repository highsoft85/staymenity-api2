<form id="upload-image-markdown-form-{{ $name }}" class="ajax-form"
      action="{{ routeCmf($sComposerRouteView.'.text.upload.post', ['id' => $oItem->id]) }}"
      method="post"
      data-callback="markdownAfterImageUpload"
      data-area="#markdown-area-{{ $name }}"
      style="display: none"
>
    <p>
        <input type="file" name="file" class="image-markdown-input-file" id="image-markdown-input-{{ $name }}" accept="image/x-png,image/gif,image/jpeg">
    </p>
    <input type="text" name="uid">
    <p>
        <input type="submit" value="Загрузить фаил">
    </p>
</form>
