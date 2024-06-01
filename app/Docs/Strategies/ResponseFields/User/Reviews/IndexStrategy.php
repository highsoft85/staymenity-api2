<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Reviews;

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
     * @var Payment
     */
    private $oItem;

    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_reviews_index;
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
                'description' => 'ID отзыва',
            ],
            'user' => [
                'type' => 'object',
                'description' => $this->listFields([
                    'id' => ['string', 'ID юзера'],
                    'first_name' => ['string', 'Имя'],
                    'last_name' => ['string|null', 'Фамилия'],
                    'image' => ['string', 'Ссылка на изображение'],
                ]),
            ],
            'description' => [
                'type' => 'string',
                'description' => 'Текст',
            ],
            'published_at' => [
                'type' => 'string',
                'description' => 'Дата в формате `2020-11-22 23:59:59`',
            ],
            'published_at_formatted' => [
                'type' => 'string',
                'description' => 'November 2020',
            ],
        ];
    }
}
