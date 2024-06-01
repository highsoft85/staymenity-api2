<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\User\Payouts\Stripe;

use App\Docs\Strategy;

class DashboardStrategy extends Strategy
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
        return $this->route_user_payouts_stripe_dashboard;
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
                    'data' => [
                        'redirect' => 'https://...',
                    ],
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
