<?php

declare(strict_types=1);

namespace App\Cmf\Project\Faq;

use App\Events\ChangeCacheEvent;
use App\Http\Controllers\Api\Index\Data;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FaqThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query;
    }
}
