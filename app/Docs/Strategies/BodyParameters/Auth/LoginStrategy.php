<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Auth;

use App\Docs\Strategy;
use App\Models\User;

class LoginStrategy extends Strategy
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
        return $this->route_auth_login;
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
            'password' => [
                'description' => 'Пароль пользователя.',
                'required' => true,
                'value' => '1234567890',
                'type' => 'string',
            ],
            'role' => [
                'description' => 'Роль на которую авторизуется пользователь. <br>' .
                    'Если роль не передается, то текущая останется той что была при регистрации',
                    'Поддерживает варианты только `' . User::ROLE_HOST . '` и `' . User::ROLE_GUEST . '`',
                'required' => false,
                'value' => User::ROLE_HOST,
                'type' => 'string',
            ],
        ];
    }
}
