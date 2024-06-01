<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Reservations\Review;

use App\Docs\Strategy;

class StoreStrategy extends Strategy
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
        return $this->route_user_reservations_review_store;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'description' => [
                'description' => 'Описание',
                'required' => true,
                'value' => 'Such an awesome space! I spent a great time with my familty at this swimming pool. Alex as host was very lovely and nice. I can’t wait to book this swimming pool again, hope summer won’t end too fast.',
                'type' => 'string',
            ],
            'rating' => [
                'description' => 'Рейтинг',
                'required' => true,
                'value' => 4,
                'type' => 'int',
            ],
        ];
    }
}
