<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Payouts;

use App\Http\Transformers\Api\PaymentTransformer;
use App\Http\Transformers\Api\PayoutTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Payment;
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
        return $this->route_user_payouts_index;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'int',
                'description' => 'ID выплаты',
            ],
            'title' => [
                'type' => 'string',
                'description' => 'Название выплаты, например Klara Jefferson, Aug 24, 9 am - 1 pm',
            ],
            'description' => [
                'type' => 'string',
                'description' => 'Описание выплаты, назание листинга, например Outdoor swimming pool with a montain view',
            ],
            'amount' => [
                'type' => 'float',
                'description' => 'Сумма выплаты',
            ],
            'status' => [
                'type' => 'object',
                'description' => $this->listFields([
                    'name' => ['string', 'Ключ статуса'],
                    'title' => ['string', 'Заголовок статуса'],
                ], 'Пока нигде не учитывать'),
            ],
            'created_at' => [
                'type' => 'string',
                'description' => 'Дата в формате `2020-12-08T04:11:27-05:00`, переводить в локальное время и в формат Aug 25, 2020',
            ],
        ];
    }
}
