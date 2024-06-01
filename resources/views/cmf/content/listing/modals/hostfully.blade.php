<?php
/** @var $oItem \App\Models\Listing */
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
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Uid</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th>Delete</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($aLeads as $aLead)
                    <tr>
                        <td>
                            {{ $aLead[\App\Services\Hostfully\Models\Leads::UID] }}
                        </td>
                        <td>
                            {{ $aLead[\App\Services\Hostfully\Models\Leads::CHECK_IN_DATE] }}
                        </td>
                        <td>
                            {{ $aLead[\App\Services\Hostfully\Models\Leads::CHECK_OUT_DATE] }}
                        </td>
                        <td>
                            {{ $aLead[\App\Services\Hostfully\Models\Leads::STATUS] }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm is-small ajax-link"
                                    action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionDevHostfullyDelete', 'id' => $oItem->id]) }}"
                                    data-view="#custom-edit-modal .modal-content.--inner"
                                    data-loading="1"
                                    data-callback="updateView"
                                    data-uid="{{ $aLead[\App\Services\Hostfully\Models\Leads::UID] }}"
                            >
                                <i class="icon-trash icons" style="color: #fff;"></i>
                            </button>
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
