<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\User\Reservations\Review;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;
use App\Models\UserSave;

class StoreStrategy extends Strategy
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
        return $this->route_user_reservations_review_store;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'int',
                'description' => 'ID брони',
                'required' => true,
                'value' => $this->factoryReservation(['user_id' => User::first()->id])->id,
            ],
        ];
    }
}
