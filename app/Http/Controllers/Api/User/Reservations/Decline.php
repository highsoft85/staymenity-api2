<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Reservations;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\User\Reservations\UpdateRequest;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\UserReservationServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Decline extends ApiController
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        $oUser = $this->authUser($request);
        /** @var Reservation|null $oItem */
        $oItem = Reservation::find($id);
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $oService = (new ReservationServiceModel($oItem))
            ->setCancelledType(Reservation::CANCELLED_TYPE_BY_HOST);
        if (!$oService->canDecline($oUser)) {
            return responseCommon()->apiErrorBadRequest([], $oService->getMessage());
        }
        $result = transaction()->commitAction(function () use ($oService) {
            $oService->setDeclined();
        });
        if (!$result->isSuccess()) {
            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
        }
        return responseCommon()->apiSuccess();
    }
}
