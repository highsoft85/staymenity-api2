<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Reservations;

use App\Docs\Strategies\Fields\PaymentCardBodyParametersTrait;
use App\Docs\Strategy;

class PaymentStrategy extends Strategy
{
    use PaymentCardBodyParametersTrait;

    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_reservations_payment;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            //'token_id' => $this->parameterTokenId(),
            //'brand' => $this->parameterBrand(),
            //'last' => $this->parameterLast(),
            //'card_id' => $this->parameterCardId(),
//            'payment_card_id' => [
//                'description' => 'ID карты из существующих (в нашей системе). Обязателен, когда нет `token_id`.',
//                'required' => false,
//                'value' => 1,
//                'type' => 'int',
//            ],
            'payment_method_id' => [
                'description' => 'Метод оплаты, для iOS',
                'required' => false,
                'value' => 1,
                'type' => 'string',
            ],
        ];
    }
}
