<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Guest;

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
        return $this->route_guest_show;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'guest/{id}',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Страница пользователя для остальных',
            'authenticated' => false,
        ];
    }
}
