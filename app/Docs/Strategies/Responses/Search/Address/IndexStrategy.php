<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\Search\Address;

use App\Docs\Strategy;

class IndexStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSES;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_search_address;
    }

    /**
     * @return array[]
     */
    public function data()
    {
        return [
//            [
//                'content' => json_encode([
//                    'success' => true,
//                    'data' => [
//                        [
//                            'title' => '22-46 78th Street',
//                            'description' => '22-46 78th Street, Woodhaven, NY, USA',
//                            'place_id' => 'EiUyMi00NiA3OHRoIFN0cmVldCwgV29vZGhhdmVuLCBOWSwgVVNBIjASLgoUChIJ6a7WYOhdwokR3-oHKfNKPPUQFioUChIJx6lgnutdwokRTXrYkztn6eI',
//                        ]
//                    ],
//                ]),
//                'status' => 200,
//            ],
            [
                'content' => json_encode([
                    'message' => 'Not found',
                    'status_code' => 404,
                ]),
                'status' => 404,
            ],
        ];
    }
}
