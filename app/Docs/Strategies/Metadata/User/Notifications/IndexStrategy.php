<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Notifications;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class IndexStrategy extends Strategy
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
        return $this->route_user_notifications_index;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'user/notifications',
            'groupDescription' => view('docs.metadata.user.notifications')->render(),
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.user.notifications.index')->render(),
            'authenticated' => true,
        ];
    }
}
