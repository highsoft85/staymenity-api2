<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth\Socialite;

use App\Docs\Strategy;

class MockSecondStrategy extends Strategy
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
        return $this->route_auth_sanctum_mock_second;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'auth/socialite',
            'groupDescription' => null,
            'title' => 'Mock Second (Only for Testing)',
            'description' => null,
            'authenticated' => false,
        ];
    }
}
