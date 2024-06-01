<?php
/** @var $oItem \App\Models\User */
?>
<div class="modal-content dialog__content">
    <div class="modal-header">
        <h4 class="modal-title">Hostfully</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-12">
            <table class="table table-sm table-hover">
                <thead>
                <tr>
                    <th>Uid</th>
                    <th>Type</th>
                    <th>Enabled</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Uid</th>
                    <th>Type</th>
                    <th>Enabled</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($aWebhookEvents as $aWebhookEvent)
                    <tr>
                        <td>
                            {{ $aWebhooks[$aWebhookEvent][\App\Services\Hostfully\Models\Webhooks::UID] ?? '-' }}
                        </td>
                        <td>
                            {{ $aWebhookEvent }}
                        </td>
                        <td>
                            <label class="switch switch-pill switch-outline-success-alt switch-sm"
                                   data-tippy-popover=""
                                   data-tippy-content="{{ isset($aWebhooks[$aWebhookEvent]) ? 'Disable' : 'Enable' }}"
                                   aria-expanded="false"
                            >
                                <input class="switch-input ajax-checkbox" type="checkbox" name="webhook"
                                       action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionDevHostfullySetWebhook', 'id' => $oItem->id]) }}"
                                       data-view="#custom-edit-modal .modal-content.--inner"
                                       data-callback="updateView"
                                       data-webhook_uid="{{ $aWebhooks[$aWebhookEvent][\App\Services\Hostfully\Models\Webhooks::UID] ?? '' }}"
                                       data-type="{{ $aWebhookEvent }}"
                                       {{  isset($aWebhooks[$aWebhookEvent]) ? 'checked' : '' }}
                                >
                                <span class="switch-slider"></span>
                            </label>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
