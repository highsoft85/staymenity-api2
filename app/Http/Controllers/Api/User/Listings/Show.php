<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Listings;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Show extends ApiController
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        $oUser = $this->authUser($request);
        /** @var Listing|null $oItem */
        $oItem = $oUser->listings()->activeForHost()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $aItem = (new ListingTransformer())->transformDetailForHost($oItem);
        return responseCommon()->apiDataSuccess($aItem);
    }
}
