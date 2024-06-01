@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.hostfully'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Hostfully',
        'description' => '',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    Rater
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        Показатель: <code>{{ $data['rater'] }} из 1000</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    Webhooks
                </div>
                <div class="card-body" style="padding: 10px">
                    <table class="table table-admin table--mobile table-hover">
                        <thead>
                        <tr>
                            <th>Uid</th>
                            <th>Type</th>
                            <th>Enabled</th>
                        </tr>
                        </thead>
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
                                               action="{{ routeCmf('listing.action.item.post', ['name' => 'actionDevHostfullySetWebhook', 'id' => 0]) }}"
                                               data-view="#custom-edit-modal .modal-content.--inner"
                                               data-callback="updateView"
                                               data-uid="{{ $aWebhooks[$aWebhookEvent][\App\Services\Hostfully\Models\Webhooks::OBJECT_UID] ?? '' }}"
                                               data-webhook_uid="{{ $aWebhooks[$aWebhookEvent][\App\Services\Hostfully\Models\Webhooks::UID] ?? '' }}"
                                               data-type="{{ $aWebhookEvent }}"
                                            {{  isset($aWebhooks[$aWebhookEvent]) ? 'checked' : '' }}
                                        >
                                        <span class="switch-slider"></span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
{{--                        --}}
{{--                        @foreach($aWebhooks as $aWebhook)--}}
{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    {{ $aWebhook[\App\Services\Hostfully\Models\Webhooks::UID] }}--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    {{ $aWebhook[\App\Services\Hostfully\Models\Webhooks::EVENT_TYPE] }}--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <label class="switch switch-pill switch-outline-success-alt switch-sm"--}}
{{--                                           data-tippy-popover=""--}}
{{--                                           data-tippy-content="Disable"--}}
{{--                                           aria-expanded="false"--}}
{{--                                    >--}}
{{--                                        <input class="switch-input ajax-checkbox" type="checkbox" name="webhook"--}}
{{--                                               action="{{ routeCmf('listing.action.item.post', ['name' => 'actionDevHostfullySetWebhook', 'id' => 0]) }}"--}}
{{--                                               data-view="#custom-edit-modal .modal-content.--inner"--}}
{{--                                               data-callback="updateView"--}}
{{--                                               data-uid="{{ $aWebhook[\App\Services\Hostfully\Models\Webhooks::OBJECT_UID] }}"--}}
{{--                                               data-webhook_uid="{{ $aWebhook[\App\Services\Hostfully\Models\Webhooks::UID] }}"--}}
{{--                                               data-type="{{ $aWebhook[\App\Services\Hostfully\Models\Webhooks::EVENT_TYPE] }}"--}}
{{--                                               checked--}}
{{--                                        >--}}
{{--                                        <span class="switch-slider"></span>--}}
{{--                                    </label>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
