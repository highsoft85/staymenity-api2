<a class="btn btn-link {{ isDownForMaintenance() ? 'text-danger' : 'text-success'}} ajax-link" href="#" data-tippy-popover
   @if(isDownForMaintenance())
   data-tippy-content="{{ __('cmf/layouts/header.maintenance.on') }}"
   @else
   data-tippy-content="{{ __('cmf/layouts/header.maintenance.off') }}"
   @endif
   action="{{ routeCmf('user.action.post', ['name' => 'actionChangeMaintenance']) }}"
   data-callback="refreshAfterSubmit"
   data-list="[data-maintenance-button-container]"
   data-list-action="{{ routeCmf('user.action.post', ['name' => 'actionCheckMaintenanceMode']) }}"
   data-loading="1"
>
    <i class="fa fa-power-off" aria-hidden="true"></i>
</a>
