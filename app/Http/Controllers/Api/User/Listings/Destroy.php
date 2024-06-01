<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Listings;

use App\Http\Controllers\Api\ApiController;
use App\Models\Listing;
use App\Models\User;
use App\Services\Image\ImageService;
use App\Services\Image\ImageType;
use App\Services\Model\ListingServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Destroy extends ApiController
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
        $oItem = $oUser->listings()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        if (!$oUser->checkListingAccess($oItem)) {
            return responseCommon()->apiErrorBadRequest([], 'Access denied');
        }
        $oService = (new ListingServiceModel($oItem));
        if ($oService->hasFutureReservations()) {
            return responseCommon()->apiError([], 'Cannot delete listing with active future reservations', 400);
        }
        $result = (new ListingServiceModel($oItem))->delete();
        if (!$result) {
            return responseCommon()->apiErrorBadRequest([], 'Error delete');
        }
        return responseCommon()->apiSuccess();
    }
}
