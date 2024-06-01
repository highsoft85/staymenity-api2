<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\Search\City;

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
        return $this->route_search_city;
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
                'description' => 'Предпологаемый адрес по поиску',
            ],
            'description' => [
                'type' => 'string',
                'description' => 'Полное название адреса, например когда штаты отличаются',
            ],
            'place_id' => [
                'type' => 'string',
                'description' => 'Гугловский `place_id` этого адреса',
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        return (new GeocoderCitiesService())->fakeCityForDocumentation()[0];
    }
}
