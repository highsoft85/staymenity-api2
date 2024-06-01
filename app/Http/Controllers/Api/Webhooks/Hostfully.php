<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Webhooks;

use App\Console\Commands\Sync\SyncReservationsCommand;
use App\Events\Webhook\WebhookSyncToEvent;
use App\Http\Requests\Api\Webhooks\HostfullyRequest;
use App\Models\HostfullyListing;
use App\Models\HostfullyWebhookResponse;
use App\Models\UserDetail;
use App\Services\Hostfully\Models\Webhooks;
use App\Services\Sync\Hostfully\HostfullyWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class Hostfully
{
    /**
     * @param Request $request
     * @return int
     */
    public function __invoke(Request $request)
    {
        $data = $request->all();
        slackInfo($data, 'HOSTFULLY WEBHOOK');

        if (isset($data['lead_uid'])) {
            HostfullyWebhookResponse::create([
                'agency_uid' => $data['agency_uid'],
                'event_type' => $data['event_type'],
                'lead_uid' => $data['lead_uid'],
                'property_uid' => $data['property_uid'],
                'status' => healthCheckHostfully()->isActive()
                    ? HostfullyWebhookResponse::STATUS_HEALTH_CHECK_ACTIVE
                    : HostfullyWebhookResponse::STATUS_HEALTH_CHECK_NOT_ACTIVE,
            ]);
        }
        if (!healthCheckHostfully()->isActive()) {
            slackInfo([], 'HOSTFULLY IS NOT ACTIVE');
            return 200;
        }

        $syncTypes = [
            Webhooks::EVENT_TYPE_NEW_BOOKING,
            Webhooks::EVENT_TYPE_NEW_INQUIRY,
            Webhooks::EVENT_TYPE_NEW_BLOCKED_DATES,
            Webhooks::EVENT_TYPE_BOOKING_CANCELLED,
        ];
        if (in_array($data['event_type'], $syncTypes) && isset($data['property_uid'])) {
            /** @var HostfullyListing|null $oItem */
            $oItem = HostfullyListing::where('uid', $data['property_uid'])->first();
            if (!is_null($oItem)) {
                Artisan::call(SyncReservationsCommand::SIGNATURE . ' --from --listing_id=' . $oItem->listing_id);
            }
        }

        if ($data['event_type'] === Webhooks::EVENT_TYPE_CHANNEL_ACTIVATED && isset($data['property_uid'])) {
            $oItem = HostfullyListing::where('uid', $data['property_uid'])->first();
            if (!is_null($oItem)) {
                $oItem->update([
                    'is_channel_active' => 1,
                ]);
                Artisan::call(SyncReservationsCommand::SIGNATURE . ' --first --listing_id=' . $oItem->listing_id);
            }
        }
        if ($data['event_type'] === Webhooks::EVENT_TYPE_CHANNEL_DEACTIVATED && isset($data['property_uid'])) {
            $oItem = HostfullyListing::where('uid', $data['property_uid'])->first();
            if (!is_null($oItem)) {
                $oItem->update([
                    'is_channel_active' => 0,
                ]);
            }
        }

        if ($data['event_type'] === Webhooks::EVENT_TYPE_INTEGRATION_ACTIVATED && isset($data['agency_uid'])) {
            /** @var UserDetail|null $oItem */
            $oItem = UserDetail::where('hostfully_agency_uid', $data['agency_uid'])->first();
            if (!is_null($oItem)) {
                $oItem->update([
                    'hostfully_status' => 1,
                ]);
                event(new WebhookSyncToEvent($oItem->user, true));
            }
        }
        if ($data['event_type'] === Webhooks::EVENT_TYPE_INTEGRATION_DEACTIVATED && isset($data['agency_uid'])) {
            $oItem = UserDetail::where('hostfully_agency_uid', $data['agency_uid'])->first();
            if (!is_null($oItem)) {
                $oItem->update([
                    'hostfully_status' => 0,
                ]);
                event(new WebhookSyncToEvent($oItem->user, false));
            }
        }

        return 200;
    }

    /**
     * @param HostfullyRequest $request
     * @return int
     */
    public function __invokeRequest(HostfullyRequest $request)
    {
        return 200;
    }
}
