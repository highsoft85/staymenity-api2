<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\Listings;

use App\Docs\Strategy;

class ShowStrategy extends Strategy
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
        return $this->route_listing;
    }

    /**
     * @return array[]
     */
    public function data()
    {
        return [
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
