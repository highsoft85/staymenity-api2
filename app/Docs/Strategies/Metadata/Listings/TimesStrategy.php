<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class TimesStrategy extends Strategy
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
        return $this->route_listings_times;
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
            'description' => 'После выбора пользователем даты - '
                . 'кидать сюда запрос на получение заблокированных часов по тайм слотам <br><br>'
                . 'По умолчанию у всех будет заблокировано с 12 AM по 11 PM, т.к. разрешенные часы 09 AM to 10 PM'
            ,
            'authenticated' => false,
        ];
    }
}
