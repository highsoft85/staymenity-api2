<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Reservations;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\Reservation;
use App\Models\UserCalendar;

class IndexStrategy extends Strategy
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
        return $this->route_user_reservations_index;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/reservations',
            'groupDescription' => '' .
                'Бронирования' .
                '',
            'title' => $this->url($this->route),
            'description' => 'Вывод всех броней пользователя<br><br>' .
                '<b>Типы:</b><br>' .
                '• `' . Reservation::SEARCH_TYPE_UPCOMING . '` (по умолчанию)<br>' .
                '• `' . Reservation::SEARCH_TYPE_PREVIOUS . '`<br>' .
                '• `' . Reservation::SEARCH_TYPE_CANCELLED . '`<br>'
            ,
            'authenticated' => true,
        ];
    }
}
