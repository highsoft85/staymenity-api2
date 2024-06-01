<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\User\Chats;

use App\Docs\Strategy;

class StoreStrategy extends Strategy
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
        return $this->route_user_chats_store;
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
                    'data' => [
                        'id' => 1,
                    ],
                ]),
                'status' => 200,
            ], [
                'content' => json_encode([
                    'success' => false,
                    'message' => 'Not found',
                    'status_code' => 404,
                ]),
                'status' => 404,
            ], [
                'content' => json_encode([
                    'message' => '422 Unprocessable Entity',
                    'errors' => [
                        'reservation_id' => __('validation.required', ['attribute' => 'reservation_id']),
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
