<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\User\Payments\Stripe;

use App\Docs\Strategy;

class EphemeralStrategy extends Strategy
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
        return $this->route_user_payments_stripe_ephemeral;
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
                        'id' => 'ephkey_::',
                        'object' => 'ephemeral_key',
                        'associated_objects' => [
                            [
                                'id' => 'cus_::',
                                'type' => 'customer',
                            ],
                        ],
                        'created' => 1605613851,
                        'expires' => 1605617451,
                        'livemode' => false,
                        'secret' => 'ek_test_::',
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
