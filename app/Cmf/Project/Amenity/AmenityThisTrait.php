<?php

declare(strict_types=1);

namespace App\Cmf\Project\Amenity;

use App\Events\ChangeCacheEvent;
use App\Http\Controllers\Api\Index\Data;
use App\Models\Amenity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait AmenityThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query->where('type', Amenity::TYPE_LISTING);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function thisCreate(Request $request)
    {
        $data = $request->all();
        $data['type'] = Amenity::TYPE_LISTING;
        Amenity::create($data);
        event(new ChangeCacheEvent(Data::CACHE_AMENITIES_KEY));
        return responseCommon()->success([]);
    }

    /**
     * @param Amenity|null $oItem
     */
    protected function thisAfterChange(?Amenity $oItem)
    {
        event(new ChangeCacheEvent(Data::CACHE_AMENITIES_KEY));
    }
}
