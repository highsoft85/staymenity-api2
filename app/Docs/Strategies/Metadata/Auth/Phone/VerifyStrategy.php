<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth\Phone;

use App\Docs\Strategy;

class VerifyStrategy extends Strategy
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
        return $this->route_auth_phone_verify;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'auth/phone',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.auth.phone.verify', [])->render(),
            'authenticated' => false,
        ];
    }
}
