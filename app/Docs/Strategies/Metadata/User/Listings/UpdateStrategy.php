<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class UpdateStrategy extends Strategy
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
        return $this->route_user_listings_update;
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
            'description' => 'Отправлять запрос на каждое сохранение. ' .
                'Так же поддерживается вариант отправлять сразу все поля. ' .
                'Необходимо выбрать вариант чтобы удобно было ошибки отлавливать вдруг что. ' .
                'Сохранение rent time и time frames еще не настроено, можно пропустить.' .
                '<br><br>' .
                '<b>Замечание 1</b><br>' .
                'Для <b>PUT</b> запроса использовать `Content-Type => application/x-www-form-urlencoded`, возможность <b>POST</b> с `multipart/form-data` сделано для удобства отправки через `postman`, т.к. пакет не вставляет данные в `x-www-form-urlencoded`, есть возможность только в `raw`.' .
                '<br><br>' .
                '<b>Замечание 2</b><br>' .
                'Ключ `amenities` передавать в `postman` в виде `amenities[]`, так же и для `images[]` и `rules[]`.' .
            '',
            'authenticated' => true,
        ];
    }
}
