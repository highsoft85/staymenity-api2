<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth\Sanctum;

use App\Docs\Strategy;

class AppleStrategy extends Strategy
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
        return $this->route_auth_sanctum_apple;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'auth/sanctum',
            'title' => 'Apple',
            'description' => null,
            'groupDescription' =>
                '<b>Авторизация для WEB</b>' . "<br><br>" .
                '<b>!!! For example !!!</b>' . "<br>" .
                '<ul>' .
                    '<li>Пользователь нажимает на кнопку `{provider}` (Авторизоваться через `{provider}`)</li>' .
                    '<li>Его редиректит на url вида `/auth/socialite/{provider}/redirect`, роут должен отрабатывать с laravel (используется Socialite), либо использовать vueвский пакет, где будут подключаться ключи и остальные параметры</li>' .
                    '<li>После его редиректит на страницу подтвержения доступа/выбора аккаунта.</li>' .
                    '<li>Если пользователь разрешает доступ, то сервис отправляет его на url вида `/auth/socialite/{provider}/callback` с необходимымы GET параметрами, которые считываются и создается/авторизуется пользователь.</li>' .
                '</ul>' .
                '<p>На фронте надо будет хватать все GET параметры и пересылать на роут вида GET `/api/sanctum/auth/{provider}/callback` и получить в ответе токен</p>' .
                '<br><p><b>Присоединение социальной сети к пользователю:</b></p>' .
                '<ul>' .
                    '<li>По клику на `Connect` редиректить пользователя как в начале</li>' .
                    '<li>... кейс как в начале ...</li>' .
                    '<li>В последнем GET `/api/sanctum/auth/{provider}/callback` только еще добавляется передача `user_id` равный текущему `id`</li>' .
                    '<li>В ответе будет новый токен, после чего пользователя надо разлогинить и заново прилогинить с новым токеном и обновленными данными, поменяется только то что у него обновились `social_accounts`, другую информацию не изменяю</li>' .
                '</ul>' .
                '',
            'authenticated' => false,
        ];
    }
}
