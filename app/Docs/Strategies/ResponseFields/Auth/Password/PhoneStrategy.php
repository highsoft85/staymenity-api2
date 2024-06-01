<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\Auth\Password;

use App\Docs\Strategy;
use App\Models\PersonalVerificationCode;

class PhoneStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_auth_password_phone;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'email' => [
                'type' => 'string',
                'description' => 'Email найденного пользователя, после его кидать в форму для сброса пароля',
            ],
            'reset_token' => [
                'type' => 'string',
                'description' => 'Токен для сброса, после его кидать в форму для сброса пароля',
            ],
        ];
    }
}
