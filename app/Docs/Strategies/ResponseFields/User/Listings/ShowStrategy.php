<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Listings;

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
        return $this->route_user_listing;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys($this->fieldsListingDetailForHost(), null, __CLASS__);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        return (new ListingTransformer())->transformDetailForHost(Listing::first());
    }
}
