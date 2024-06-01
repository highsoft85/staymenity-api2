<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\Auth\Verify;

use App\Docs\Strategy;

class Failed extends Strategy
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
        return $this->route_auth_verify_failed;
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
                    'message' => 'Verify failed success',
                ]),
                'status' => 200,
            ], [
                'content' => json_encode([
                    'message' => '422 Unprocessable Entity',
                    'errors' => [
                        'email' => 'The email must be a valid email address.',
                    ],
                    'status_code' => 422,
                ]),
                'status' => 422,
            ],
        ];
    }
}
