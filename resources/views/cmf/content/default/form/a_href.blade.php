<a href="#"
   class="trigger {{ $class ?? '' }}"
   data-dialog="#custom-edit-modal"
   data-ajax
   data-action="{{ $action }}"
   @if(isset($ajax_init))data-ajax-init="{{ $ajax_init }}" @endif
   @if(isset($callback))data-callback="{{ $callback }}" @endif
   @if(isset($table_hide))data-table-hide="{{ $table_hide }}" @endif
   @if(isset($tippy_popover))data-tippy-popover="{{ $tippy_popover }}" @endif
   @if(isset($tippy_content))data-tippy-content="{{ $tippy_content }}" @endif
   @if(isset($style))style="{{ $style }}"@endif
>{{ $slot }}</a>
