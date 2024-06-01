<?php
/** @var \App\Models\User $oItem */
?>
<a class="avatar {{ isset($withoutTitle) && $withoutTitle ? '' : 'mr-1' }}  {{ \Illuminate\Support\Facades\Auth::user()->id === $oItem->id && $model === 'user' ? 'border-success' : '' }}"
    data-fancybox="gallery-post-{{ $model }}"
    href="{{ $oItem->image_original }}"
    @if(\Illuminate\Support\Facades\Auth::user()->id === $oItem->id)
        data-tippy-popover
        data-tippy-content="It's You"
    @else
        @if(isset($withoutTitle) && $withoutTitle)
            data-tippy-popover
            data-tippy-content="{{ $oItem->searchName }}"
        @endif
    @endif
>
    <img class="img-avatar" src="{{ $oItem->image_xs }}" width="30">
    @if(method_exists($oItem, 'trashed') && !is_null($oItem->deleted_at))
        <span class="avatar-status badge-default"></span>
    @else
        <span class="avatar-status {{ $oItem->isActive() ? 'badge-success' : 'badge-danger' }}"></span>
    @endif
</a>
