<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Models\Listing;
use App\Models\User;
use App\Services\Image\ImageService;
use App\Services\Image\ImageType;
use App\Services\Model\UserServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Destroy
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        /** @var User $oUser */
        $oUser = $request->user();

        $oService = (new UserServiceModel($oUser));
        if ($oService->hasFutureReservationsForListings()) {
            return responseCommon()->apiError([], 'Cannot delete account with active future reservations for your listings', 400);
        }
        if ($oService->hasFutureReservations()) {
            return responseCommon()->apiError([], 'Cannot delete account with active future reservations', 400);
        }

        $result = $oService->delete();
        if (!$result->isSuccess()) {
            return responseCommon()->apiError([], $result->getErrorMessage(), 400);
        }
        return responseCommon()->apiSuccess();
    }
}
