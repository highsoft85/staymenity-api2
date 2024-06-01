<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class SimilarStrategy extends Strategy
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
        return $this->route_listings_similar;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'listings',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Выбираются 8(максимум) листингов, которые по дистанции до 50 миль и тип такой же. <br>' .
                'Если пустой массив, то блок не выводить' .
                '',
            'authenticated' => false,
        ];
    }
}
