<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Notifications;

use App\Http\Transformers\Api\FirebaseNotificationTransformer;
use App\Http\Transformers\Api\NotificationTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Docs\Strategy;
use App\Models\UserSave;
use App\Notifications\User\HaveNewMessageNotification;
use App\Notifications\User\LeaveReviewNotification;
use App\Notifications\User\TestNotification;

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
        return $this->route_user_notifications_index;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys([
            'id' => [
                'type' => 'string',
                'description' => null,
            ],
            'type' => [
                'type' => 'string',
                'description' => 'Тип уведомления' . '<br><b>Доступные:</b><br>' .
                    '• `' . LeaveReviewNotification::NAME . '` <br>' .
                    '• `' . HaveNewMessageNotification::NAME . '` <br>' .
                '',
            ],
            'message' => [
                'type' => 'string',
                'description' => 'Текст сообщения',
            ],
            'extend' => [
                'type' => 'object',
                'description' => $this->listFields([
                    'image' => ['string', 'Ссылка на изображение, автарку | `type=' . LeaveReviewNotification::NAME . '`'],
                    'reservation_id' => ['int', 'ID брони | `type=' . LeaveReviewNotification::NAME . '`, ID для GET `/user/reservations/:id/review` в котором можно получиться title и description формы'],
                ], 'Дополнительная информация, по типу выводятся разные необходимые данные'),
            ],
            'created_at' => [
                'type' => 'string',
                'description' => 'Дата в формате `2020-12-08T04:11:27-05:00`',
            ],
            'created_at_formatted' => [
                'type' => 'string',
                'description' => 'Не использовать! Дата в формате 11-09-2020 12:11 PM, с учетом Today и Yesterday',
            ],
        ]);
    }


    /**
     * @return array
     */
    protected function transformerKeys()
    {
        /** @var User $oUser */
        $oUser = User::first();
        return (new NotificationTransformer())->transform($this->factoryUserNotificationLeaveReview($oUser));
    }
}
