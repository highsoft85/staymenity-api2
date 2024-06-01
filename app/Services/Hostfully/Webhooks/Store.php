<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Webhooks;

use App\Models\Listing;
use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\Models\Webhooks;

class Store extends BaseHostfullyService
{
    /**
     * @param array $data
     * @return array
     */
    public function __invoke(array $data): array
    {
        $data = $this->apiPostRaw('/webhooks', $data);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }

    /**
     * @param Listing $oListing
     * @param string $type
     * @return array
     */
    public function listing(Listing $oListing, string $type)
    {
        $data = [
            'agencyUid' => config('hostfully.agencyUid'),
            'objectUid' => config('hostfully.agencyUid'),
            'webHookType' => 'POST_JSON',
            'eventType' => $type,
            'callbackUrl' => config('hostfully.webhooks.url'),
        ];
        return $this->__invoke($data);
    }

    /**
     * @param string $agencyUid
     * @param string $type
     * @return array
     */
    public function agency(string $agencyUid, string $type)
    {
        $integrations = [
            Webhooks::EVENT_TYPE_CHANNEL_ACTIVATED,
            Webhooks::EVENT_TYPE_CHANNEL_DEACTIVATED,
            Webhooks::EVENT_TYPE_INTEGRATION_ACTIVATED,
            Webhooks::EVENT_TYPE_INTEGRATION_DEACTIVATED,
        ];
        if (in_array($type, $integrations)) {
            $data = [
                'objectUid' => config('hostfully.api.key'),
                'webHookType' => 'POST_JSON',
                'eventType' => $type,
                'callbackUrl' => hostfullyWebhooksUrl(),
            ];
        } else {
            $data = [
                'agencyUid' => $agencyUid,
                'objectUid' => $agencyUid,
                'webHookType' => 'POST_JSON',
                'eventType' => $type,
                'callbackUrl' => hostfullyWebhooksUrl(),
            ];
        }
        return $this->__invoke($data);
    }
}
