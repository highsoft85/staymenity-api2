<?php
    $type = $type ?? 'edit';
    $model = $model ?? null;
    $model = str_replace('_', '-', $model);
?>
@if(count($oItem->comments) !== 0)
    @foreach($oItem->comments as $oComment)
        <div class="form-group is-comments-group" style="position: relative; border-bottom: 1px solid #eee;">
            <label class="text-muted m-0">
                {{ $oComment->created_at->format('d.m.Y H:i:s') }}
            </label>
            -
            {{ $oComment->commented->first_name }}
            {{ $oComment->commented->last_name }}
{{--            <span class="text-muted">{{ $oComment->commented->role_name ?? 'Неизвестно' }}</span>--}}
            <br>
            <div class="markdown-front">
                {!! $oComment->text !!}
            </div>
            <div class="mb-1 text-muted">
                <span>{{$oComment->comment_type_name}}</span>
            </div>
            @if($type === 'edit')
                <div style="position:absolute;top: 0;right: 0" class="d-flex align-items-center">
                    <div class="mr-1">
                        {{ $oComment->votes()->sum('value') }}
                    </div>
                    <a href="#" class="btn btn-danger btn-sm btn-outline-danger ajax-link"
                       action="{{ routeCmf('comment.action.item.post', ['id' => $oComment->id, 'name' => 'actionDeleteComment']) }}"
                       data-loading="1"
                       data-callback="commentsUpdateAfterSubmit"
                       data-model="{{ $model }}"
                    >
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>
                </div>
            @endif
        </div>
    @endforeach
@else
    <p class="text-center text-muted">
        Empty
    </p>
@endif


