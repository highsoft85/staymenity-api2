<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\Auth\Phone;

use App\Docs\Strategy;
use App\Models\PersonalVerificationCode;

class VerifyStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_auth_phone_verify;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'token' => [
                'type' => 'string',
                'description' => 'Только для `type=' . PersonalVerificationCode::TYPE_LOGIN . '` <br>' .
                    'Если существует, то сохранять в какое-нибудь хранилище и авторизовывать пользователя.',
            ],
        ];
    }
}
