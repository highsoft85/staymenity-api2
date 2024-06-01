<?php
/** @var \App\Models\User $oItem */
?>
<div class="--view-local-tokens-table">
    <table class="table table-borderless table-sm table-hover">
        <thead>
        <tr>
            <th>Token</th>
            <th>Name</th>
            <th>Last Used At</th>
            <th>Created At</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Token</th>
            <th>Name</th>
            <th>Last Used At</th>
            <th>Created At</th>
        </tr>
        </tfoot>
        <tbody>
        @foreach($oItem->tokens()->get() as $oToken)
            <tr>
                <td>
                    <input type="text" class="form-control" value="{{ $oToken->token }}"/>
                </td>
                <td>{{ $oToken->name }}</td>
                <td>
                    {{ !is_null($oToken->last_used_at) ? $oToken->last_used_at->format('d.m.Y H:i:s') : '-' }}
                </td>
                <td>
                    {{ !is_null($oToken->created_at) ? $oToken->created_at->format('d.m.Y H:i:s') : '-' }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div>
        <a class="btn btn-primary text-white ajax-link"
           action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'actionGetAdminToken']) }}"
           data-view=".--view-local-tokens-table"
           data-callback="replaceView"
           data-loading="1"
        >
            Get Admin Token
        </a>
    </div>
    @if(isset($token))
        <div class="mt-1">
            <div class="form-group">
                <label>Token</label>
                <input type="text" class="form-control" placeholder="Token" value="{{ $token }}">
            </div>
        </div>
    @endif
</div>
