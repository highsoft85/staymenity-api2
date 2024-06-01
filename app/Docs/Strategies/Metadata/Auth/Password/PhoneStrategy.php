<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth\Password;

use App\Docs\Strategy;

class PhoneStrategy extends Strategy
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
        return $this->route_auth_password_phone;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'auth/password',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.auth.password.phone', [])->render(),
            'authenticated' => false,
        ];
    }
}
