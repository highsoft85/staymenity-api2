<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Listings\Calendar;

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
        return $this->route_user_listings_calendar_index;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/listings/calendar',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Вывод дат для календаря',
            'authenticated' => true,
        ];
    }
}
