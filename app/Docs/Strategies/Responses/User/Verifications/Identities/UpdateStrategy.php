<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\User\Verifications\Identities;

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
        return $this->route_user_verifications_identities_update;
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
                    'data' => [],
                ]),
                'status' => 200,
            ], [
                'content' => json_encode([
                    'success' => false,
                    'errors' => [
                        'image_front' => 'Cannot analyze image',
                    ],
                    'status_code' => 422,
                ]),
                'status' => 422,
            ], [
                'content' => json_encode([
                    'message' => '422 Unprocessable Entity',
                    'errors' => [
                        'step' => __('validation.required', ['attribute' => 'step']),
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
