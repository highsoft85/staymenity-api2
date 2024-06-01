<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Listings;

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
        return $this->route_listing;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'listings',
            'groupDescription' => null,
            'title' => 'api/listings/{id}',
            'description' => null,
            'authenticated' => false,
        ];
    }
}
