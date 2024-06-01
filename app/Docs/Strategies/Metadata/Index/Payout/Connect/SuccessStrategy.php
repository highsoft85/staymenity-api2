<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Index\Payout\Connect;

use App\Docs\Strategy;

class SuccessStrategy extends Strategy
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
        return $this->route_payout_connect_success;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'index',
            'groupDescription' => null,
            'title' => 'api/payout/connect/success',
            'description' => 'Payout connect',
            'authenticated' => false,
        ];
    }
}
