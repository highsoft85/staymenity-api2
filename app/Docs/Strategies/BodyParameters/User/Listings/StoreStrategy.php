<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class StoreStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

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
            'type_id' => [
                'description' => 'ID типа',
                'required' => true,
                'value' => 1,
                'type' => 'int',
            ],
            'type_other' => [
                'description' => 'Кастомный тип, чтобы сохранить его - должен быть выбран `type_id` с `name = other`',
                'required' => false,
                'value' => 'Other type description',
                'type' => 'string',
            ],
            'guests_size' => [
                'description' => 'Вместимость',
                'required' => true,
                'value' => 8,
                'type' => 'int',
            ],
            'address' => [
                'description' => 'Адрес листинга строкой пока',
                'required' => true,
                'value' => '9279 Central Ave. Brooklyn, NY 11230',
                'type' => 'string',
            ],
            'place_id' => [
                'description' => 'Гугловский place_id этого адреса',
                'required' => true,
                'value' => 'EigyMi00NiA3OHRoIFN0cmVldCwgTm9ydGggQmVyZ2VuLCBOSiwgVVNBIjASLgoUChIJV6pwn9j3wokRQYh0s73IAhIQFioUChIJ65S-8uD3wokR55U7X6gf5ws',
                'type' => 'string',
            ],
        ];
    }
}
