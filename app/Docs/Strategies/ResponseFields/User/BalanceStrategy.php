<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User;

use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Docs\Strategy;

class BalanceStrategy extends Strategy
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
        return $this->route_user_balance;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return [
            'available' => [
                'type' => 'int|float',
                'description' => 'Сумма доступная для вывода, её выводить',
            ],
            'pending' => [
                'type' => 'int|float',
                'description' => 'Сумма которая еще не доступна для вывода, пока нигде не выводить',
            ],
        ];
    }
}
