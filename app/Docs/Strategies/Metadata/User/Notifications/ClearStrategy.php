<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Notifications;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class ClearStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_METADATA;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_notifications_clear;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/notifications',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => null,
            'authenticated' => true,
        ];
    }
}
