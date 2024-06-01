<?php

declare(strict_types=1);

namespace App\Cmf\Project\Request;

use App\Events\ChangeCacheEvent;
use App\Models\Request;
use Illuminate\Database\Eloquent\Builder;

trait RequestThisTrait
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

    /**
     *
     */
    public function thisBeforePaginate()
    {
        Request::where('status', Request::STATUS_UNREAD)->update([
            'status' => Request::STATUS_ACTIVE,
        ]);
        $this->thisAfterChange();
    }
}
