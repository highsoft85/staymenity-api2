<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Listings;

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
        return $this->route_user_listings_store;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/listings',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Сам запрос отправлять в самом конце первого шага (ввод адреса), ' .
                'после в `data` будет получен ключ `id` и дальнейшие запросы будут на обновление этого листинга. ' .
                'Например PUT /api/user/listings/{id}',
            'authenticated' => true,
        ];
    }
}
