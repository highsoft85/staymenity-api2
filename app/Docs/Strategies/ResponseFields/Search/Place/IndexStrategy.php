<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\Search\Place;

use App\Http\Controllers\Api\IndexController;
use App\Docs\Strategy;
use App\Services\Geocoder\GeocoderCitiesService;

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
        return $this->route_search_place;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys([
            'title' => [
                'type' => 'string',
                'description' => 'Заголовок адреса',
            ],
            'description' => [
                'type' => 'string',
                'description' => 'Полное название',
            ],
            'country' => [
                'type' => 'object|null',
                'description' => $this->listFields([
                    'title' => 'Полное название',
                    'code' => 'Код страны',
                ]),
            ],
            'state' => [
                'type' => 'object|null',
                'description' => $this->listFields([
                    'title' => 'Полное название',
                    'code' => 'Код штата',
                ], 'Может быть `null`'),
            ],
            'city' => [
                'type' => 'string|null',
                'description' => 'Может быть `null`',
            ],
            'zip' => [
                'type' => 'string',
                'description' => 'Индекс',
            ],
            'coordinates' => [
                'type' => 'object',
                'description' => $this->listFields([
                    'latitude' => 'Широта',
                    'longitude' => 'Долгота',
                ]),
            ],
            'point' => [
                'type' => 'array',
                'description' => 'Координаты, 0 - latitude, 1 - longitude',
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        return (new GeocoderCitiesService())->fakePlaceForDocumentation();
    }
}
