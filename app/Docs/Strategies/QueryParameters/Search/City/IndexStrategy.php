<?php

declare(strict_types=1);

namespace App\Docs\Strategies\QueryParameters\Search\City;

use App\Docs\Strategy;

class IndexStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_QUERY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_search_city;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'q' => [
                'description' => 'Искомое значение',
                'required' => true,
                'value' => 'Barce',
            ],
        ];
    }
}
