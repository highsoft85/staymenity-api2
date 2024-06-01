<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\Auth\Phone;

use App\Docs\Strategy;

class VerifyStrategy extends Strategy
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
        return $this->route_auth_phone_verify;
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
                    'success' => true,
                    'message' => 'Login success',
                    'data' => [
                        'token' => '617|OSFtHVofrDTY8PkI94mi7zg1kNLNEOh3NyNODldt3lyaVaY1S02ANdNYLkn0zS0ODHh5d278OAkrZGyK',
                    ],
                ]),
                'status' => 200,
            ], [
                'content' => json_encode([
                    'message' => '422 Unprocessable Entity',
                    'errors' => [
                        'phone' => 'The phone must be a valid phone.',
                        'code' => 'The code must be a valid code.',
                    ],
                    'status_code' => 422,
                ]),
                'status' => 422,
            ],
        ];
    }
}
