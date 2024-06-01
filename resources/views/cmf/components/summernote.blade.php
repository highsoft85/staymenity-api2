{{--
    'url' => url('/admin/posts/action/upload-wysiwyg'),
    'model' => 'post',
    'oItem' => $oPost,
    'id' => 'summernote',
    'itemText' => $oPost->text
--}}

<div class="row">
    <div class="col-12">
        {{-- --article-html-content - класс как в читалке --}}
        <div class="form-group --{{ $model }}-html-content" style="margin-bottom: 0;">
            <input id="{{ $id ?? 'summernote' }}-uid" type="hidden" name="uid" value="{{ now()->format('YmdHi') }}-{{ \Illuminate\Support\Str::random(16) }}">
            <textarea id="{{ $id ?? 'summernote' }}-textarea" class="textarea" name="text" style="display: none;">
                @if(isset($itemText))
                    {{ $itemText }}
                @else
                    {{ $oItem->text ?? ''}}
                @endif
            </textarea>
            <article>
                <div id="{{ $id ?? 'summernote' }}"
                     class="summernote-editor"

                     data-text-area="{{ $id ?? 'summernote' }}-textarea"
                     data-text-block="{{ $id ?? 'summernote' }}-textblock"

                     data-url="{{ $url }}"
                     data-upload-url="{{ $uploadUrl ?? '' }}"
                     data-imageable_type="{{ $model }}"
                >

                </div>
            </article>
        </div>
    </div>
</div>
