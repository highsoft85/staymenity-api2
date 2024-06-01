<a class="avatar {{ \Illuminate\Support\Facades\Auth::user()->id === $oItem->id ? 'border-success' : '' }}"
   data-fancybox="gallery-post-{{ $model }}"
   href="{{ $oItem->image_original }}"
   @if(\Illuminate\Support\Facades\Auth::user()->id === $oItem->id)
        data-tippy-popover
        data-tippy-content="It's You"
   @endif
>
    <img class="img-avatar" src="{{ $oItem->image_xs }}" width="30">
</a>
