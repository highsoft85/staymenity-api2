<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Auth\Verify;

use Illuminate\Support\Str;
use App\Docs\Strategy;

class Success extends Strategy
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
        return $this->route_auth_verify_success;
    }

    /**
     * @return array[]
     */
    public function data()
    {
        return [
            'token' => [
                'description' => 'Токен из ссылки в электронном письме',
                'required' => true,
                'value' => Str::random(16),
                'type' => 'string',
            ],
            'email' => [
                'description' => 'Email пользователя',
                'required' => true,
                'value' => 'admin@admin.com2',
                'type' => 'string',
            ],
        ];
    }
}
