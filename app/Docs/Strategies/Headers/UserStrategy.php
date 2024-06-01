<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Headers;

use App\Docs\Strategy;

class UserStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_HEADERS;

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
            'Authorization' => 'Bearer {YOUR_AUTH_KEY}',
        ];
    }
}
