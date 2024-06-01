<?php

declare(strict_types=1);

namespace App\Cmf\Project\Feedback;

use App\Events\ChangeCacheEvent;
use App\Http\Controllers\Api\Index\Data;
use App\Models\Feedback;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FeedbackThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query;
    }

    public function thisAfterChange()
    {
        foreach ($this->cache as $value) {
            event(new ChangeCacheEvent($value));
        }
    }
}
