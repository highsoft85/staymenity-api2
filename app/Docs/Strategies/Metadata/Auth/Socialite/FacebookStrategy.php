<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth\Socialite;

use App\Docs\Strategy;

class FacebookStrategy extends Strategy
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
        return $this->route_auth_socialite_facebook;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'auth/socialite',
            'groupDescription' => null,
            'title' => 'Facebook',
            'description' => null,
            'authenticated' => false,
        ];
    }
}
