<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Reservations\Review;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

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
        return $this->route_user_reservations_review_index;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'user/reservations/{id}/review',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Запрос на тексты в окне отзыва, с проверками на возможность оставить отзыв',
            'authenticated' => true,
        ];
    }
}
