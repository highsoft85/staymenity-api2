<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Chats;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Device;
use App\Models\Listing;
use App\Docs\Strategy;

class StoreStrategy extends Strategy
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
        return $this->route_user_chats_store;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'reservation_id' => [
                'description' => 'ID брони',
                'required' => true,
                'value' => 1,
                'type' => 'int',
            ],
        ];
    }
}
