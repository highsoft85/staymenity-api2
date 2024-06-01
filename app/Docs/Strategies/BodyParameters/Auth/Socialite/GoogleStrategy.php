<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Auth\Socialite;

use App\Docs\Strategy;
use App\Models\User;

class GoogleStrategy extends Strategy
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
        return $this->route_auth_socialite_google;
    }

    /**
     * @return array[]
     */
    public function data()
    {
        return [
            'access_token' => [
                'type' => 'string',
                'description' => 'Access Token после открытия доступа',
                'required' => true,
                'value' => '::token::',
            ],
            'role' => [
                'type' => 'string',
                'description' => 'Роль пользователя',
                'required' => true,
                'value' => User::ROLE_HOST,
            ],
            'user_id' => [
                'type' => 'integer',
                'description' => 'ID юзера, неоьходимо для Connect социальной сети',
                'required' => false,
                'value' => 1,
            ],
        ];
    }
}
