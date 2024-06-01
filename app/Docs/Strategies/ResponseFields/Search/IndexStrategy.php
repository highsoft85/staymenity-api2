<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\Search;

use App\Http\Controllers\Api\IndexController;
use App\Docs\Strategy;
use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;

class IndexStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_search;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys($this->fieldsListingCard());
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        return (new ListingTransformer())->transformCard($this->factoryListing());
    }
}
