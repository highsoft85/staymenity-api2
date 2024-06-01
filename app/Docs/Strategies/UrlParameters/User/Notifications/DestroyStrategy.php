<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\User\Notifications;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;
use App\Models\UserSave;

class DestroyStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_URL_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_notifications_destroy;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'string',
                'description' => 'ID уведомления',
                'required' => true,
                'value' => '9c1a816c-1a64-4893-aefd-f2e9b42a0fee',
            ],
        ];
    }
}
