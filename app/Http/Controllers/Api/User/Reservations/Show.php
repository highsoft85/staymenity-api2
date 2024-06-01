<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Reservations;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\User\Reservations\StoreRequest;
use App\Http\Transformers\Api\ReservationTransformer;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\UserReservationServiceModel;
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

        /** @var Reservation|null $oReservation */
        $oReservation = $oUser->reservations()->where('id', $id)->first();
        if (is_null($oReservation)) {
            return responseCommon()->apiNotFound();
        }
        $oHost = $oReservation->listing->user;
        if (is_null($oHost)) {
            return responseCommon()->apiNotFound();
        }

        $aItem = (new ReservationTransformer())->transformDetail($oReservation);
        return responseCommon()->apiDataSuccess($aItem);
    }
}
