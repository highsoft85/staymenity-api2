<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\Auth;

use App\Docs\Strategy;

class ResetPasswordStrategy extends Strategy
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
        return $this->route_auth_reset_password;
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
                    'message' => __('auth.login_success'),
                    'data' => [
                        'token' => '617|OSFtHVofrDTY8PkI94mi7zg1kNLNEOh3NyNODldt3lyaVaY1S02ANdNYLkn0zS0ODHh5d278OAkrZGyK',
                    ],
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
            ], [
                'content' => json_encode([
                    'message' => '422 Unprocessable Entity',
                    'errors' => [
                        'email' => __('passwords.token'),
                    ],
                    'status_code' => 422,
                ]),
                'status' => 422,
            ],
        ];
    }
}
