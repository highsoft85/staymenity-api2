<?php

declare(strict_types=1);

namespace App\Cmf\Project\Payout;

use App\Events\ChangeCacheEvent;
use App\Http\Controllers\Api\Index\Data;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait PayoutThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query;
        //return $query->where('type', Payment::TYPE_LISTING);
    }

    public function thisDestroy($oItem)
    {
        // заглушка чтобы не было удаления
    }
}
