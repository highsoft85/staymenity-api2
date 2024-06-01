<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class ChatStrategy extends Strategy
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
        return $this->route_listings_chat;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'listings',
            'groupDescription' => null,
            'title' => 'api/listings/{id}/chat',
            'description' => 'Получение чата для листинга, если не существует, то создаст новый',
            'authenticated' => true,
        ];
    }
}
