<?php

declare(strict_types=1);

namespace App\Cmf\Project\Rule;

use App\Events\ChangeCacheEvent;
use App\Http\Controllers\Api\Index\Data;
use App\Models\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait RuleThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    public function thisQuery(Builder $query)
    {
        return $query->where('type', Rule::TYPE_LISTING);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function thisCreate(Request $request)
    {
        $data = $request->all();
        $data['type'] = Rule::TYPE_LISTING;
        Rule::create($data);
        event(new ChangeCacheEvent(Data::CACHE_RULES_KEY));
        return responseCommon()->success([]);
    }

    /**
     * @param Rule|null $oItem
     */
    protected function thisAfterChange(?Rule $oItem)
    {
        event(new ChangeCacheEvent(Data::CACHE_RULES_KEY));
    }
}
