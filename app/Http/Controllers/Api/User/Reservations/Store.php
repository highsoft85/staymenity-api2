<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Reservations;

use App\Events\Reservation\ReservationSyncToEvent;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\User\Reservations\StoreRequest;
use App\Models\Listing;
use App\Models\User;
use App\Services\Model\UserReservationServiceModel;
use Illuminate\Http\JsonResponse;

class Store extends ApiController
{
    /**
     * @param StoreRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(StoreRequest $request)
    {
        $oUser = $this->authUser($request);

        if (cmfIsAdminVisual($request)) {
            $data = $request->query->all();
        } else {
            $data = $request->validated();
        }

        /** @var Listing|null $oItem */
        $oItem = Listing::active()->where('id', $data['listing_id'])->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        if ($oItem->user_id === $oUser->id) {
            return responseCommon()->apiErrorBadRequest();
        }
        // проверка свободности даты
        $oUserReservationServiceModel = (new UserReservationServiceModel($oItem, $oUser));
        if (!$oUserReservationServiceModel->checkTimesByData($data, $oItem->timezone)) {
            return responseCommon()->apiError([], __('reservation.dates_locked'), 404);
        }
        // проверка что у хоста есть вывод
        if (!$oUserReservationServiceModel->checkHostHasPayoutConnect() && config('staymenity.reservation_check_host_payout_connect')) {
            return responseCommon()->apiError([], __('reservation.host_without_payout'), 404);
        }
        // создание брони
        $result = transaction()->commitAction(function () use ($oUserReservationServiceModel, $data) {
            $oUserReservationServiceModel->create($data);
        });
        if (!$result->isSuccess()) {
            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
        }
        $oReservation = $oUserReservationServiceModel->getReservation();

        if (!is_null($oItem->hostfully) && config('staymenity.reservation_sync_after_store')) {
            event(new ReservationSyncToEvent($oReservation));
        }

        return responseCommon()->apiDataSuccess([
            'id' => $oReservation->id,
        ]);
    }
}
