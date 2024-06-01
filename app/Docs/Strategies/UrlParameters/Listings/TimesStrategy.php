<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;

class TimesStrategy extends Strategy
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
        return $this->route_listings_times;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'int',
                'description' => 'ID листинга',
                'required' => true,
                'value' => $this->factoryListing(['user_id' => User::first()->id])->id,
            ],
        ];
    }
}
