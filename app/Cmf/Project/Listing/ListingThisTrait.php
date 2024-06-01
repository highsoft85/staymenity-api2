<?php

declare(strict_types=1);

namespace App\Cmf\Project\Listing;

use App\Models\Amenity;
use App\Models\Listing;
use App\Models\Rule;
use App\Models\User;
use App\Services\Model\ListingServiceModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait ListingThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query;//->withTrashed();
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function thisCreate(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = $data['user'];
        $data['type_id'] = $data['type'];
        $data['creator_id'] = Auth::user()->id;
        /** @var Listing $oItem */
        $oItem = Listing::create($data);
        $this->saveRelationships($oItem, $request);
        $oItem->settings()->create([
            'cancellation_description' => $data['cancellation_description'] ?? null,
        ]);
        Session::put('last_create', $oItem->id);
        return responseCommon()->success([]);
    }

    /**
     * @param Request $request
     * @param Listing $oItem
     * @return array
     */
    protected function thisUpdate(Request $request, Listing $oItem)
    {
        $data = $request->all();
        $data['user_id'] = $data['user'];
        $data['type_id'] = $data['type'];
        $oItem->update($data);
        $this->saveRelationships($oItem, $request);
        $oItem->settings()->update([
            'cancellation_description' => $data['cancellation_description'] ?? null,
        ]);
        if (is_null($oItem->published_at)) {
            (new ListingServiceModel($oItem))->setStatusUnlist();
        }
        return responseCommon()->success([]);
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    public function thisEditDataModal(Listing $oItem)
    {
        $oAmenities = Amenity::ordered()->get();
        $aActiveAmenities = $oItem->amenitiesActive()->get()->pluck('id')->toArray();
        $oAmenities = $oAmenities->transform(function (Amenity $item) use ($aActiveAmenities) {
            $item->hasAmenity = in_array($item->id, $aActiveAmenities);
            return $item;
        });

        $oRules = Rule::ordered()->get();
        $aActiveRules = $oItem->rulesActive()->get()->pluck('id')->toArray();
        $oRules = $oRules->transform(function (Rule $item) use ($aActiveRules) {
            $item->hasRule = in_array($item->id, $aActiveRules);
            return $item;
        });

        return [
            'oAmenities' => $oAmenities,
            'oRules' => $oRules,
        ];
    }

    /**
     * @param Listing $oListing
     */
    public function thisDestroy(Listing $oListing)
    {
        $oService = (new ListingServiceModel($oListing));
        if ($oService->hasFutureReservations()) {
            throw new \Exception('Cannot delete listing with active future reservations');
        }
        (new ListingServiceModel($oListing))->delete();
//        if (request()->exists('force') && (int)request()->get('force') === 1) {
//            $oListing->forceDelete();
//        }
    }
}
