<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Chats;

use App\Docs\Strategies\Fields\Reservation\ReservationFieldsTrait;
use App\Http\Transformers\Api\ChatTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Chat;
use App\Models\User;
use App\Docs\Strategy;
use App\Models\UserSave;

class IndexStrategy extends Strategy
{
    use ReservationFieldsTrait;

    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_chats_index;
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
                'description' => 'ID чата',
            ],
            'image' => [
                'type' => 'string',
                'description' => 'Изображение чата',
            ],
            'title' => [
                'type' => 'string',
                'description' => 'Заголовок чата, гость видит имя хоста, хост видит имя гостя',
            ],
            'last_message' => [
                'type' => 'string',
                'description' => 'Текст последнего сообщения',
            ],
            'last_message_at' => [
                'type' => 'string',
                'description' => 'Дата последнего изображения в формате 2019-02-01T03:45:27+00:00 по ISO_8601, на выходе преобразовывать в локальную дату, например moment("2019-02-01T03:45:27+00:00", moment.ISO_8601)->format("hh:mm");',
            ],
            'reservation' => [
                'type' => 'object|null',
                //'description' => view('docs.fields.reservation.forChat')->render(),
                'description' => $this->listFields($this->reservationTransformForChat(), 'Бронь', false),
            ],
            'listing' => [
                'type' => 'object',
                //'description' => view('docs.fields.reservation.forChat')->render(),
                'description' => $this->listFields($this->listingTransformForChat(), 'Листинг', false),
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        /** @var Chat $oChat */
        $oChat = $this->user()->chatsActive()->first();
        return (new ChatTransformer())->transformForHost($oChat);
    }
}
