<div>
    {{ $oItem->title }}
    <br>
    <small
{{--        data-tippy-popover data-tippy-content="{{ $oItem->description }}"--}}
    >
        {{ \Illuminate\Support\Str::limit($oItem->description, 30) }}
    </small>
</div>
