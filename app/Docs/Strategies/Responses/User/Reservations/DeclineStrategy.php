<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\User\Reservations;

use App\Docs\Strategy;

class DeclineStrategy extends Strategy
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
        return $this->route_user_reservations_decline;
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
                    'success' => false,
                    'message' => 'Access denied',
                    'status_code' => 401,
                ]),
                'status' => 401,
            ], [
                'content' => json_encode([
                    'message' => 'Unauthorized',
                    'status_code' => 401,
                ]),
                'status' => 401,
            ],
        ];
    }
}
