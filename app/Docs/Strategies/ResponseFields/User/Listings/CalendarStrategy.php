<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\UserCalendar;

class CalendarStrategy extends Strategy
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
        return $this->route_user_listings_calendar_index;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return [
            'title' => [
                'type' => 'string',
                'description' => 'Заголовок месяца',
            ],
            'weeks' => [
                'type' => 'array of objects',
                'description' => 'Массив с массивами объектов, в каждой неделе содержится день с ключом дня, котором:<br>' .
                    '• `null` - закрыта дата, т.е. дата прошлого месяца<br>' .
                    '• `object` - объект с полями `day`, `week`, `type`, `is_disabled`<br><br>' .
                    '<b>Типы:</b><br>' .
                    '• `' . UserCalendar::TYPE_AVAILABLE . '` - свободен, по умолчанию<br>' .
                    '• `' . UserCalendar::TYPE_LOCKED . '` - заблокирован пользователем<br>' .
                    '• `' . UserCalendar::TYPE_BOOKED . '` - есть бронь<br>' .
                    '• `' . UserCalendar::TYPE_DISABLED . '` - заблокирован системой (по идеи использоваться не будет)<br><br>' .
                    'Для отслеживания прошедших - `is_disabled`, т.к. в прошедней дате может быть бронь и надо бы её показать, но с прозрачностью<br>' .
                    '',
            ],
        ];
    }
}
