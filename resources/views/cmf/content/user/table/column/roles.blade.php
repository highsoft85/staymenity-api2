<?php
/** @var \App\Models\User $oItem */
?>
{{--@if($oItem->roles()->count() > 1)--}}
{{--    @foreach($oItem->roles as $role)--}}
{{--        <span class="text-nowrap">{{ $role->title }}</span><br>--}}
{{--    @endforeach--}}
{{--@else--}}
{{--    {{ $oItem->roleName }}--}}
{{--@endif--}}

<div>
    @foreach($oItem->roleIcons as $icon)
        <a class="btn btn-sm {{ $icon['color'] }}" role="button"
           data-tippy-popover
           data-tippy-content="{{ $icon['title'] }}"
           @if($oItem->current_role !== null && $oItem->current_role !== $icon['name'])
           style="filter: grayscale(1);"
           @endif
        >
            <i class="{{ $icon['class'] }}" aria-hidden="true"></i>
        </a>
    @endforeach
</div>


