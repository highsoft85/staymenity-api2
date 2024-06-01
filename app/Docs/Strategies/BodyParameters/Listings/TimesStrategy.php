<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class TimesStrategy extends Strategy
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
        return $this->route_listings_times;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'date' => [
                'description' => 'Выбранная дата в формате Y-m-d',
                'required' => true,
                'value' => now()->format('Y-m-d'),
                'type' => 'string',
            ],
        ];
    }
}
