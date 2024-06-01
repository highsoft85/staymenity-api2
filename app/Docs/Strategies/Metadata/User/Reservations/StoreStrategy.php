<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Reservations;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class StoreStrategy extends Strategy
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
        return $this->route_user_reservations_store;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/reservations',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Бронирование для авторизованного пользователя, подробности по /api/reservations',
            'authenticated' => true,
        ];
    }
}
