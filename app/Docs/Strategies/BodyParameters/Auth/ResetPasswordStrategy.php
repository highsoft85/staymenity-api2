<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Auth;

use Illuminate\Support\Str;
use App\Docs\Strategy;

class ResetPasswordStrategy extends Strategy
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
        return $this->route_auth_reset_password;
    }

    /**
     * @return array[]
     */
    public function data()
    {
        return [
            'token' => [
                'description' => 'Токен из ссылки в электронном письме. Передавать в скрытом виде.',
                'required' => true,
                'value' => Str::random(16),
                'type' => 'string',
            ],
            'email' => [
                'description' => 'Email пользователя. Передавать в скрытом виде.',
                'required' => true,
                'value' => 'admin@admin.com2',
                'type' => 'string',
            ],
            'password' => [
                'description' => 'Новый пароль пользователя.',
                'required' => true,
                'value' => '1234567890',
                'type' => 'string',
            ],
            'password_confirmation' => [
                'description' => 'Подтверждение пароля.',
                'required' => true,
                'value' => '1234567890',
                'type' => 'string',
            ],
        ];
    }
}
