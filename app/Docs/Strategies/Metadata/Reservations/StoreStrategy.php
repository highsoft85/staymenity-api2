<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Reservations;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class StoreStrategy extends Strategy
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
        return $this->route_reservations_store;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'reservations',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.reservations.store')->render(),
            'authenticated' => true,
        ];
    }
}
