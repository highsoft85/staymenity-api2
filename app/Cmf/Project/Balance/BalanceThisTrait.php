<?php

declare(strict_types=1);

namespace App\Cmf\Project\Balance;

use App\Events\ChangeCacheEvent;
use App\Http\Controllers\Api\Index\Data;
use App\Models\Balance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait BalanceThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query;
        //return $query->where('type', Balance::TYPE_LISTING);
    }

    public function thisDestroy($oItem)
    {
        // заглушка чтобы не было удаления
    }
}
