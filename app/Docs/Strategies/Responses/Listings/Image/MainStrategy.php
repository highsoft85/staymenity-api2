<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\Listings\Image;

use App\Docs\Strategy;

class MainStrategy extends Strategy
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
        return $this->route_listings_image_main;
    }

    /**
     * @return array[]
     */
    public function data()
    {
        return [
            [
                'content' => json_encode([
                    'success' => true,
                    'message' => 'Success',
                ]),
                'status' => 200,
            ], [
                'content' => json_encode([
                    'message' => 'Access denied',
                    'status_code' => 422,
                ]),
                'status' => 422,
            ],
        ];
    }
}
