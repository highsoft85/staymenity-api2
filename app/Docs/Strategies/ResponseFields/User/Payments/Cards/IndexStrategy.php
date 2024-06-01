<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Payments\Cards;

use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Docs\Strategy;
use App\Models\UserSave;
use App\Notifications\User\LeaveReviewNotification;

class IndexStrategy extends Strategy
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
        return $this->route_user_payments_cards_index;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
//        $this->factoryPaymentCard([
//            'user_id' => User::first()->id,
//        ]);
        return [
            'id' => [
                'type' => 'string',
                'description' => 'Зашифрованный ID payment_method_id, например eyJpdiI6Ik9pc1J***',
            ],
            'payment_method_id' => [
                'type' => 'string',
                'description' => 'pm_1HoU8rIFDQsDl8swmmE6e1O5 например',
            ],
            'brand' => [
                'type' => 'string',
                'description' => 'Бренд карты, например Visa',
            ],
            'last' => [
                'type' => 'string',
                'description' => 'Последние 4 цифры',
            ],
            'is_main' => [
                'type' => 'bool',
                'description' => 'Является ли карта картой по умолчанию',
            ],
        ];
    }
}
