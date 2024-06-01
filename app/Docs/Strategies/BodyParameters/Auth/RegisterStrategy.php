<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Auth;

use App\Docs\Strategy;

class RegisterStrategy extends Strategy
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
        return $this->route_auth_register;
    }

    /**
     * @return array[]|null
     */
    public function data()
    {
        return [
            'first_name' => [
                'description' => 'Имя пользователя.',
                'required' => true,
                'value' => 'Admin',
                'type' => 'string',
            ],
            'last_name' => [
                'description' => 'Фамилия пользователя.',
                'required' => true,
                'value' => 'Admin',
                'type' => 'string',
            ],
            'email' => [
                'description' => 'Email пользователя.',
                'required' => false,
                'value' => 'admin@admin.com2',
                'type' => 'string',
            ],
            'password' => [
                'description' => 'Пароль пользователя. Обязательно когда есть `email`',
                'required' => false,
                'value' => '1234567890',
                'type' => 'string',
            ],
            'phone' => [
                'description' => 'Телефон пользователя. Для регистрации по телефону передавать в скрытом виде.',
                'required' => true,
                'value' => '99999999999',
                'type' => 'string',
            ],
            'phone_verified' => [
                'description' => 'Подтвержден ли телефон у пользователя. Для регистрации по телефону имеет значение `1`, по `email` - `0`, именно в int.',
                'required' => true,
                'value' => 1,
                'type' => 'int',
            ],
        ];
    }
}
