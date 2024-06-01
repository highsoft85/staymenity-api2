<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Payments;

use App\Http\Transformers\Api\PaymentTransformer;
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
        return $this->route_user_payments_index;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys([
            'id' => [
                'type' => 'int',
                'description' => 'ID платежа',
            ],
            'title' => [
                'type' => 'string',
                'description' => 'Название платежа, например Klara Jefferson, Aug 24, 9 am - 1 pm',
            ],
            'description' => [
                'type' => 'string',
                'description' => 'Описание платежа, назание листинга, например Outdoor swimming pool with a montain view',
            ],
            'amount' => [
                'type' => 'float',
                'description' => 'Сумма платежа',
            ],
            'status' => [
                'type' => 'object',
                'description' => $this->listFields([
                    'name' => ['string', 'Ключ статуса'],
                    'title' => ['string', 'Заголовок статуса'],
                ]),
            ],
            'created_at' => [
                'type' => 'string',
                'description' => 'Дата в формате `2020-10-15 12:29:23`',
            ],
            'created_at_formatted' => [
                'type' => 'string',
                'description' => 'Дата в формате Aug 25, 2020',
            ],
        ]);
    }


    /**
     * @return array
     */
    protected function transformerKeys()
    {
        /** @var Payment $oPayment */
        $oPayment = $this->user()->paymentsToMe()->first();
        return (new PaymentTransformer())->transformForHost($oPayment);
    }
}
