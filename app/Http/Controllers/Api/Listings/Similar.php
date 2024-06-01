<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Services\Model\ListingServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Similar
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        /** @var Listing|null $oItem */
        $oItem = Listing::active()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $oListings = (new ListingServiceModel($oItem))->getSimilar(4);
        $aItems = $oListings->transform(function (Listing $item) {
            return (new ListingTransformer())->transformCard($item);
        })->toArray();
        return responseCommon()->apiDataSuccess($aItems);
    }
}
