<?php

declare(strict_types=1);

namespace App\Services\Sync\Hostfully;

use App\Models\User;
use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\Models\Webhooks;
use App\Services\Hostfully\Webhooks\Index;
use App\Services\Hostfully\Webhooks\Store;

class HostfullyWebhookService
{
    /**
     * @return array
     */
    public function integrationActivate()
    {
        return (new Store())->__invoke([
            'eventType' => Webhooks::EVENT_TYPE_INTEGRATION_ACTIVATED,
            'webHookType' => Webhooks::WEB_HOOK_TYPE_POST_JSON,
            'callbackUrl' => hostfullyWebhooksUrl(),
            'objectUid' => config('hostfully.token'),
        ]);
    }

    /**
     * @return array
     */
    public function integrationDeactivate()
    {
        return (new Store())->__invoke([
            'eventType' => Webhooks::EVENT_TYPE_INTEGRATION_DEACTIVATED,
            'webHookType' => Webhooks::WEB_HOOK_TYPE_POST_JSON,
            'callbackUrl' => hostfullyWebhooksUrl(),
            'objectUid' => config('hostfully.token'),
        ]);
    }

    /**
     * @param string $propertyUid
     * @return array
     */
    public function activateNewBooking(string $propertyUid)
    {
        return (new Store())->__invoke([
            'eventType' => Webhooks::EVENT_TYPE_NEW_BOOKING,
            'webHookType' => Webhooks::WEB_HOOK_TYPE_POST_JSON,
            'callbackUrl' => hostfullyWebhooksUrl(),
            'objectUid' => $propertyUid,
            'agencyUid' => config('hostfully.agencyUid'),
        ]);
    }

    /**
     * @param User $oUser
     */
    public function deactivateIntegrations(User $oUser)
    {
        $aWebhookEvents = [
            Webhooks::EVENT_TYPE_NEW_BOOKING,
            Webhooks::EVENT_TYPE_BOOKING_CANCELLED,
            Webhooks::EVENT_TYPE_NEW_BLOCKED_DATES,
            Webhooks::EVENT_TYPE_NEW_INQUIRY,
        ];
        $aWebhooks = (new Index())->__invoke($oUser->details->hostfully_agency_uid);
        foreach ($aWebhooks as $aWebhook) {
            slackInfo(['user_id' => $oUser->id, 'uid' => $aWebhook[Webhooks::UID]], 'WEBHOOK DISABLE TYPE ' . $aWebhook[Webhooks::EVENT_TYPE]);
            (new \App\Services\Hostfully\Webhooks\Destroy())->__invoke($aWebhook[Webhooks::UID]);
        }
    }

    /**
     * @param User $oUser
     */
    public function activateIntegrations(User $oUser)
    {
        $aWebhookEvents = [
            Webhooks::EVENT_TYPE_NEW_BOOKING,
            Webhooks::EVENT_TYPE_BOOKING_CANCELLED,
            Webhooks::EVENT_TYPE_NEW_BLOCKED_DATES,
            Webhooks::EVENT_TYPE_NEW_INQUIRY,
        ];
        $aWebhooks = (new Index())->__invoke($oUser->details->hostfully_agency_uid);
        $aWebhooks = collect($aWebhooks)->groupBy(Webhooks::EVENT_TYPE)->toArray();
        foreach ($aWebhookEvents as $event) {
            if (!isset($aWebhooks[$event])) {
                slackInfo(['user_id' => $oUser->id, 'event' => $event], 'WEBHOOK ENABLE TYPE ' . $event);
                (new \App\Services\Hostfully\Webhooks\Store())->agency($oUser->details->hostfully_agency_uid, $event);
            }
        }
    }
}
