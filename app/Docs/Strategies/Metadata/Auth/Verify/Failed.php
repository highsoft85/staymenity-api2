<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Auth\Verify;

use App\Docs\Strategy;

class Failed extends Strategy
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
        return $this->route_auth_verify_failed;
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
                '<b>Блокировка пользователя</b>' .
                '<ul>' .
                    '<li>Пользователю на почту так же приходит ссылка вида `/auth/verify/failed?token={token}&email={email}`</li>' .
                    '<li>Этот роут надо будет отследить и кидать запрос POST `/api/auth/verify/failed` со всеми параметрами</li>' .
                    '<li>После, если пользователь был авторизован, разлогинить его, мб удалить/очистить ключ</li>' .
                '</ul>' .
                '' .
            '',
            'authenticated' => false,
        ];
    }
}
