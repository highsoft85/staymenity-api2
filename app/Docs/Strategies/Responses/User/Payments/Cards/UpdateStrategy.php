<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\User\Payments\Cards;

use App\Docs\Strategy;

class UpdateStrategy extends Strategy
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
        return $this->route_user_payments_cards_update;
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
                    'message' => 'Unauthorized',
                    'status_code' => 401,
                ]),
                'status' => 401,
            ],
        ];
    }
}
