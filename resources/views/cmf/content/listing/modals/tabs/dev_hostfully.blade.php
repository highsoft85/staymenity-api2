<?php
/** @var \App\Models\User $oItem */
?>
<div class="--view-local-tokens-table">
    <table class="table table-borderless table-sm table-hover">
        <thead>
        <tr>
            <th>Uid</th>
            <th>Listing</th>
            <th>Status</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Uid</th>
            <th>Listing</th>
            <th>Status</th>
            <th>Delete</th>
        </tr>
        </tfoot>
        <tbody>
        @foreach($aLeads as $aLead)
            <tr>
                <td>
                    {{ $oLead[\App\Services\Hostfully\Models\Leads::UID] }}
                </td>
                <td>
                    {{ $oLead[\App\Services\Hostfully\Models\Leads::PROPERTY_UID] }}
                </td>
                <td>
                    {{ $oLead[\App\Services\Hostfully\Models\Leads::STATUS] }}
                </td>
                <td>Delete</td>
            </tr>
        @endforeach
        </tbody>
    </table>
{{--    <div>--}}
{{--        <a class="btn btn-primary text-white ajax-link"--}}
{{--           action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionGetAdminToken']) }}"--}}
{{--           data-view=".--view-local-tokens-table"--}}
{{--           data-callback="replaceView"--}}
{{--           data-loading="1"--}}
{{--        >--}}
{{--            Get Admin Token--}}
{{--        </a>--}}
{{--    </div>--}}
{{--    @if(isset($token))--}}
{{--        <div class="mt-1">--}}
{{--            <div class="form-group">--}}
{{--                <label>Token</label>--}}
{{--                <input type="text" class="form-control" placeholder="Token" value="{{ $token }}">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
</div>
