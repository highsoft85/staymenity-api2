<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class ShowStrategy extends Strategy
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
        return $this->route_listing;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys($this->fieldsListingDetail(), null, __CLASS__);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        return (new ListingTransformer())->transformDetail(Listing::first());
    }
}
