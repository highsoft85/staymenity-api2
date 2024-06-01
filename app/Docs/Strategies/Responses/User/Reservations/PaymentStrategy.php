<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\User\Reservations;

use App\Docs\Strategy;

class PaymentStrategy extends Strategy
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
        return $this->route_user_reservations_payment;
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
                    'message' => 'Your card was declined.',
                    'status_code' => 400,
                ]),
                'status' => 400,
            ], [
                'content' => json_encode([
                    'message' => '422 Unprocessable Entity',
                    'errors' => [
                        'payment_method_id' => __('validation.required', ['attribute' => 'payment_method_id']),
                    ],
                    'status_code' => 422,
                ]),
                'status' => 422,
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
