<?php
/** @var \App\Models\Review $oItem */
/** @var \App\Models\User|\App\Models\Listing $oModel */
$oModel = $oItem->model()->withTrashed()->first();
?>
@if(!is_null($oModel))
    @if($oModel instanceof \App\Models\User)
        <div class="d-flex align-items-center justify-content-start" style="width: 300px">
            <div>
                @include('cmf.components.user.avatar', [
                    'oItem' => $oModel,
                    'model' => $model,
                ])
            </div>
            <div>
                #{{ $oModel->id }}: {{ $oModel->fullName }}
                <br>
                <small>{{ $oModel->email }}</small>
            </div>
        </div>
    @elseif($oModel instanceof \App\Models\Listing)
        <div class="d-flex align-items-center justify-content-start" style="width: 300px">
            <div>
                @include('cmf.components.user.avatar', [
                    'oItem' => $oModel,
                    'model' => $model,
                ])
            </div>
            <div>
                #{{ $oModel->id }}: {{ $oModel->title }}
                <br>
                <small>
                    {{ \Illuminate\Support\Str::limit($oModel->description, 30) }}
                </small>
            </div>
        </div>
    @endif
@endif

