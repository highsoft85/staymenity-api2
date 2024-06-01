<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;

class UpdateStrategy extends Strategy
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
        return $this->route_user_update;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'current_role' => [
                'description' => 'Текущая роль, для определения дашбоарда. ' .
                    'Поддерживает варианты только `' . User::ROLE_HOST . '` и `' . User::ROLE_GUEST . '` <br>' .
                    'Отправлять запрос только как пользовать хочет перейти на дашбоард другой роли. ' .
                    'При общем обновлении - НЕ ОТПРАВЛЯТЬ.',
                'required' => false,
                'value' => User::ROLE_HOST,
                'type' => 'string',
            ],
            'first_name' => [
                'description' => 'Имя',
                'required' => false,
                'value' => 'First',
                'type' => 'string',
            ],
            'last_name' => [
                'description' => 'Фамилия',
                'required' => false,
                'value' => 'Last',
                'type' => 'string',
            ],
            'gender' => [
                'description' => 'Пол, список возможных вариантов выше в About',
                'required' => false,
                'value' => 1,
                'type' => 'int',
            ],
            'email' => [
                'description' => 'Email пользователя',
                'required' => false,
                'value' => 'Description',
                'type' => 'string',
            ],
            'phone' => [
                'description' => 'Телефон пользователя',
                'required' => false,
                'value' => '99999999999',
                'type' => 'string',
            ],
            'birthday_at' => [
                'description' => 'Дата рождения в формате m/d/Y',
                'required' => false,
                'value' => '10/22/2020',
                'type' => 'string',
            ],
            'description' => [
                'description' => 'Описание пользвоателя',
                'required' => false,
                'value' => 'Description',
                'type' => 'string',
            ],
            'place_id' => [
                'description' => 'Гугловский place_id этого адреса, тут указаное фейкове place_id чтобы не дергать гугл',
                'required' => false,
                'value' => placeFake(),
                'type' => 'string',
            ],
            'image' => [
                'description' => 'Один бинарный файл',
                'required' => false,
                'value' => storage_path('tests/default.jpg'),
                'type' => 'file',
            ],
            'current_password' => [
                'description' => 'Текущий пароль',
                'required' => false,
                'value' => '1234567890',
                'type' => 'string',
            ],
            'new_password' => [
                'description' => 'Новый пароль, обязателен, когда есть `current_password`',
                'required' => false,
                'value' => '12345678901',
                'type' => 'string',
            ],
            'new_password_confirmation' => [
                'description' => 'Подтверждение нового пароля, обязателен, когда есть `new_password`',
                'required' => false,
                'value' => '12345678901',
                'type' => 'string',
            ],
        ];
    }
}
