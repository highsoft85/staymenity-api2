<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Reservations;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\User\Reservations\UpdateRequest;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Model\UserReservationServiceModel;
use Illuminate\Http\JsonResponse;

class Update extends ApiController
{
    /**
     * @param UpdateRequest $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(UpdateRequest $request, int $id)
    {
        $oUser = $this->authUser($request);

        $data = $request->validated();

        /** @var Reservation|null $oItem */
        $oItem = Reservation::find($id);
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        return responseCommon()->apiSuccess();
    }
}
