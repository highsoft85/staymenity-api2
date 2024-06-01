<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata;

use App\Docs\Strategy;

class UserStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_METADATA;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user',
            'groupDescription' => null,
            'title' => 'api/user',
            'description' => "Данные пользователя по `Bearer Token`. Для документации берется первый найденный.<br>" .
                "Если `phone != null` и `is_phone_verified=false`, то сразу кидать запрос на отправку кода и показывать форму `Verify your phone number` для ввода этого кода, подробнее см в `Регистрации по email`.<br>",
            'authenticated' => true,
        ];
    }
}
