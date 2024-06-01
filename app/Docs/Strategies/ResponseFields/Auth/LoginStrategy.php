<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\Auth;

use App\Docs\Strategy;

class LoginStrategy extends Strategy
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
        return $this->route_auth_login;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'token' => [
                'type' => 'string',
                'description' => 'Если существует, то сохранять в какое-нибудь хранилище и авторизовывать пользователя.',
            ],
        ];
    }
}
