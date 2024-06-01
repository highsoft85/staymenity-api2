<?php

declare(strict_types=1);

namespace App\Cmf\Project\Review;

use App\Events\ChangeCacheEvent;
use App\Http\Controllers\Api\Index\Data;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ReviewThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query;
        //return $query->where('type', Review::TYPE_LISTING);
    }
}
