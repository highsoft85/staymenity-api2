<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\User\Social;

use App\Docs\Strategy;

class DestroyStrategy extends Strategy
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
        return $this->route_user_social_destroy;
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
                ]),
                'status' => 200,
            ], [
                'content' => json_encode([
                    'success' => false,
                    'message' => 'Not found',
                    'status_code' => 404,
                ]),
                'status' => 404,
            ],
        ];
    }
}
