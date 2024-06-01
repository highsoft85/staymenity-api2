<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

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
        return $this->route_user_listings;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys($this->fieldsListingCardForHost(), null, __CLASS__);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        return (new ListingTransformer())->transformCardForHost(Listing::first());
    }
}
