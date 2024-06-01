<?php
    $rows = $rows ?? 5;
    $alias = $alias ?? $name ?? '';
    $title = $title ?? 'Подробно';
    $placeholder = $placeholder ?? $title;
    $value = $oItem->{$alias} ?? $default ?? '';
    $uid = \Illuminate\Support\Str::random(32);
?>

<div class="markdown-editor-wrap markdown-wrapper-{{ $name }}">
    <label>
        {{ $title }}
    </label>
    <div class="markdown-editor-buttons {{ isset($textable) && $textable ? 'with-columns' : '' }}">
        <div class="btn-group">
            <button type="button" class="btn btn-light btn-sm icon markdown-add-bold">
                <i class="fa fa-bold" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm icon markdown-add-italic">
                <i class="fa fa-italic" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm icon markdown-add-quote">
                <i class="fa fa-quote-right" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm icon markdown-add-code">
                <i class="fa fa-code" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm icon markdown-add-link">
                <i class="fa fa-link" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm icon markdown-add-bullet-list">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm icon markdown-add-numbered-list">
                <i class="fa fa-list-ol" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm icon markdown-add-task-list">
                <i class="fa fa-tasks" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm icon markdown-add-table">
                <i class="fa fa-table" aria-hidden="true"></i>
            </button>
        </div>
        <div class="btn-group">
            @if(isset($textable) && $textable)
                <button type="button" class="btn btn-light btn-sm icon trigger" style="padding: inherit;"
                        data-dialog="#custom-edit-modal-support"
                        data-ajax
                        data-action="{{ routeCmf('user.action.post', ['name' => 'actionModalMarkdownEmoji']) }}"
                >
                    😀
                </button>
                <button type="button" class="btn btn-light btn-sm icon markdown-columns-toggler" title="Предпросмотр">
                    <i class="fa fa-columns"></i>
                </button>
            @endif
            <button type="button" class="btn btn-light btn-sm icon markdown-toggler" title="Предпросмотр">
                <i class="fa fa-eye"></i>
            </button>
        </div>
    </div>
    <textarea class="form-control markdown drop-area" cols="15" rows="{{ $rows }}" placeholder="{!! $placeholder !!}" name="{{ $name }}">{{ $value }}</textarea>
    @if(isset($imageable) && $imageable)
        <div class="clearfix">
            <i class="note-icon-picture" onclick="$(this).closest('.markdown-editor-wrap').find('.image-markdown-input').click();" style="cursor: pointer; float: right; margin-top: 3px;">
                Прикрепить картинку
            </i>
        </div>
        <input type="file" name="file" id="file-markdown-image-{{ $oItem->id }}"
               class="btn btn-primary file-uploader is-file image-markdown-input hidden --form-ignore"
               accept="image/*"
               data-dropzone='.dropzone[data-id="file-markdown-image-{{ $oItem->id }}"]'
               data-url="{{ routeCmf($model.'.text.upload.post', ['id' => $oItem->id]) }}"
               data-id="{{ $oItem->id }}"
               data-uid="{{ $uid }}"
               data-loading-container='.markdown-wrapper-{{ $name }}'
               multiple
        >
        <input type="hidden" name="uid" value="{{ $uid }}">
    @endif

    <div class="markdown-container">
        <div class="markdown-window hidden">
            <span class="badge badge-default">Предпросмотр</span>
            <div class="markdown-preview"></div>
        </div>
        @if(isset($textable) && $textable)
            <div class="markdown-window hidden is-columns">
                <div class="markdown-preview"></div>
            </div>
        @endif
    </div>
</div>
