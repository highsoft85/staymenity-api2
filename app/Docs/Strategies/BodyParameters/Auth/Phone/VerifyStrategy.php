<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Auth\Phone;

use App\Docs\Strategy;

class VerifyStrategy extends Strategy
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
        return $this->route_auth_phone_verify;
    }

    /**
     * @return array[]
     */
    public function data()
    {
        return [
            'phone' => [
                'description' => 'Телефон пользователя. Обязательно когда не передается `user_id`.',
                'required' => false,
                'value' => '99999999999',
                'type' => 'string',
            ],
            'code' => [
                'description' => 'Введенный код.',
                'required' => true,
                'value' => '123456',
                'type' => 'string',
            ],
            'user_id' => [
                'description' => 'ID пользователя. Обязательно когда не передается `phone`.',
                'required' => false,
                'value' => 1,
                'type' => 'int',
            ],
        ];
    }
}
