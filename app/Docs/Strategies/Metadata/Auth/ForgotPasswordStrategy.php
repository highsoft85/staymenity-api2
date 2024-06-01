<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth;

use App\Docs\Strategy;

class ForgotPasswordStrategy extends Strategy
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
        return $this->route_auth_forgot_password;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'auth',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => '' .
                '<b>Отправка ссылки на сброс пароля</b>' .
                '<ul>' .
                    '<li>Пользователь вводит `email` и отправляет запрос на POST `/api/auth/password/email`, ответе 200 будет `message="Link sent successfully.", можно его вывести`</li>' .
                    '<li>Дальше пользователю приходит письмо см. `Сброс пароля`</li>' .
                '</ul>' .
                '' .
            '',
            'authenticated' => false,
        ];
    }
}
