<?php

declare(strict_types=1);

namespace App\Docs\Strategies\QueryParameters\Search\Place;

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
        return $this->route_search_place;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'q' => [
                'description' => 'Искомое значение, должен содержать place_id',
                'required' => true,
                'value' => 'EiUyMi00NiA3OHRoIFN0cmVldCwgV29vZGhhdmVuLCBOWSwgVVNBIjASLgoUChIJ6a7WYOhdwokR3-oHKfNKPPUQFioUChIJx6lgnutdwokRTXrYkztn6eI',
            ],
        ];
    }
}
