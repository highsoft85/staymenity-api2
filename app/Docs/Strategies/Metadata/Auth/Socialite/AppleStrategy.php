<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth\Socialite;

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
        return $this->route_auth_socialite_apple;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'auth/socialite',
            'title' => 'Apple',
            'description' => null,
            'groupDescription' => '<b>Авторизация для IOS</b>' . "<br><br>" .
                '',
            'authenticated' => false,
        ];
    }
}
