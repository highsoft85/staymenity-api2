<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth;

use App\Docs\Strategy;

class ResetPasswordStrategy extends Strategy
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
        return $this->route_auth_reset_password;
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
                '<b>Сброс пароля</b>' .
                '<ul>' .
                    '<li>Пользователю приходит письмо с ссылкой вида `/auth/password/reset?token={token}&email={email}`</li>' .
                    '<li>Этот роут надо будет отследить, собрать все параметры и вывести форму с вводом нового пароля</li>' .
                    '<li>Далее пользователь отправляет запрос POST `/api/auth/password/reset` с всеми GET параметрами</li>' .
                    '<li>Если ответ 200, `success=true` и есть в data `token`, то сохранять его и перекидывать на главную и кидать запрос на `/api/user`,</li>' .
                '</ul>' .
                '' .
            '',
            'authenticated' => false,
        ];
    }
}
