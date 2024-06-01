<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Listings\Calendar;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\UserCalendar;

class UpdateStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_listings_calendar_update;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'date' => [
                'description' => 'Дата в формате Y-m-d',
                'required' => false,
                'value' => now()->format('Y-m-d'),
                'type' => 'string',
            ],
            'type' => [
                'description' => 'Тип назначения, поддерживаются только варианты ' .
                    '`' . UserCalendar::TYPE_AVAILABLE . '`, ' .
                    '`' . UserCalendar::TYPE_LOCKED . '`, ' .
                    '`' . UserCalendar::TYPE_BOOKED . '`' .
                    '',
                'required' => false,
                'value' => UserCalendar::TYPE_AVAILABLE,
                'type' => 'string',
            ],
            'action' => [
                'description' => 'Общие экшены, поддерживаются варианты ' .
                    '`' . UserCalendar::ACTION_UNLOCK_ALL . '`, ' .
                    '`' . UserCalendar::ACTION_UNLOCK_WEEKENDS . '`, ' .
                    '`' . UserCalendar::ACTION_UNLOCK_WEEKDAYS . '`' .
                    '',
                'required' => false,
                'value' => UserCalendar::ACTION_UNLOCK_ALL,
                'type' => 'string',
            ],
        ];
    }
}
