<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\Search\Place;

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
        return $this->route_search_place;
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
//                        'title' => '90-22 78th St',
//                        'description' => '90-22 78th St, Woodhaven, NY 11421, USA',
//                        'country' => [
//                            'title' => 'United States',
//                            'code' => 'US',
//                        ],
//                        'state' => [
//                            'title' => 'New York',
//                            'code' => 'NY',
//                        ],
//                        'city' => 'Woodhaven',
//                        'zip' => '11421',
//                        'coordinates' => [
//                            'latitude' => 40.6872293,
//                            'longitude' => -73.8630396,
//                        ],
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
