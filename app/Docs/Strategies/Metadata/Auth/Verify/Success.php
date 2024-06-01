<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth\Verify;

use App\Docs\Strategy;

class Success extends Strategy
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
        return $this->route_auth_verify_success;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'auth/verify',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => '' .
                '<b>Подтверждение email</b>' .
                '<ul>' .
                    '<li>Пользователю на почту так же приходит ссылка вида `/auth/verify/success?token={token}&email={email}`</li>' .
                    '<li>Этот роут надо будет отследить и кидать запрос POST `/api/auth/verify/success` со всеми параметрами</li>' .
                    '<li>После успешного ответа просто показывать уведомление и все, можно обновить юзера</li>' .
                '</ul>' .
                '' .
            '',
            'authenticated' => false,
        ];
    }
}
