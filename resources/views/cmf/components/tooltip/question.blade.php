<a role="button"
   class="text-default"
   data-tippy-popover
   data-tippy-content="{{ $title ?? '' }}"
   {{--data-tippy="{{ $title ?? '' }}"--}}
   href="#"
>
    <i class="fa fa-{{ isset($icon) ? $icon : 'question-circle-o' }}" aria-hidden="true"></i>
</a>
