<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth;

use App\Docs\Strategy;

class RegisterStrategy extends Strategy
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
        return $this->route_auth_register;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'auth',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.auth.register', [])->render(),
            'authenticated' => false,
        ];
    }
}
