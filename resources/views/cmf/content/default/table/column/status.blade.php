@if(method_exists($oItem, 'statuses'))
    @if(method_exists($oItem, 'trashed') && !is_null($oItem->deleted_at))
        <span class="badge badge-default">
            Deleted
        </span>
    @else
        <span class="badge {{ $oItem->status_icon['class'] }}">
            {{ $oItem->status_text }}
        </span>
    @endif
@else
    @if($oItem->status === 1)
        <span class="badge badge-success">Active</span>
    @else
        <span class="badge badge-default">Inactive</span>
    @endif
@endif
