<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Notifications;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Device;
use App\Models\Listing;
use App\Docs\Strategy;

class IndexStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_notifications_index;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'no_read' => [
                'description' => 'Отключить автопрочтение',
                'required' => false,
                'value' => 1,
                'type' => 'int',
            ],
        ];
    }
}
