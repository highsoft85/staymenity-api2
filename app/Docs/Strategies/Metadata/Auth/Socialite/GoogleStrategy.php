<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth\Socialite;

use App\Docs\Strategy;

class GoogleStrategy extends Strategy
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
        return $this->route_auth_socialite_google;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'auth/socialite',
            'groupDescription' => null,
            'title' => 'Google',
            'description' => null,
            'authenticated' => false,
        ];
    }
}
