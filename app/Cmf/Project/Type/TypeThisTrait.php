<?php

declare(strict_types=1);

namespace App\Cmf\Project\Type;

use App\Events\ChangeCacheEvent;
use App\Http\Controllers\Api\Index\Data;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\Type;
use App\Services\Model\ListingServiceModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait TypeThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query->where('type', Type::TYPE_LISTING)->withTrashed();
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function thisCreate(Request $request)
    {
        $data = $request->all();
        $data['type'] = Type::TYPE_LISTING;
        Type::create($data);
        event(new ChangeCacheEvent(Data::CACHE_TYPES_KEY));
        return responseCommon()->success([]);
    }

    /**
     * @param Type|null $oItem
     */
    protected function thisAfterChange(?Type $oItem)
    {
        event(new ChangeCacheEvent(Data::CACHE_TYPES_KEY));
    }

    public function thisDestroy($oItem)
    {
        $oItem->update([
            'status' => Type::STATUS_NOT_ACTIVE,
        ]);
        $oListings = Listing::where('type_id', $oItem->id)->get();
        foreach ($oListings as $oListing) {
            (new ListingServiceModel($oListing))->delete();
        }
        $oItem->delete();
        if (request()->exists('force') && (int)request()->get('force') === 1) {
            $oItem->forceDelete();
        }
    }
}
