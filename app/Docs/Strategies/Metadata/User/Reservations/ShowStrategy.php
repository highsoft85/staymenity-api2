<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Reservations;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class ShowStrategy extends Strategy
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
        return $this->route_user_reservations_show;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/reservations/{id}',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Достать информацию о брони, используется для оплаты из Bookings',
            'authenticated' => true,
        ];
    }
}
