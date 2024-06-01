<?php

declare(strict_types=1);

namespace App\Cmf\Project\Reservation;

use App\Cmf\Core\Parameters\TableParameter;
use App\Events\ChangeCacheEvent;
use App\Http\Controllers\Api\Index\Data;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ReservationThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query;
        //return $query->where('type', Reservation::TYPE_LISTING);
    }

    public function thisDestroy($oItem)
    {
        // заглушка чтобы не было удаления
    }
}
