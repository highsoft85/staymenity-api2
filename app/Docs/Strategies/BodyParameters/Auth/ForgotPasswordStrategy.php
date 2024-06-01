<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Auth;

use App\Docs\Strategy;

class ForgotPasswordStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_auth_forgot_password;
    }

    /**
     * @return array[]
     */
    public function data()
    {
        return [
            'email' => [
                'description' => 'Email пользователя.',
                'required' => true,
                'value' => 'admin@admin.com2',
                'type' => 'string',
            ],
        ];
    }
}
