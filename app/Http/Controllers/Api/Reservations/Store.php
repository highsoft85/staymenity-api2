<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Reservations;

use App\Events\Reservation\ReservationSyncToEvent;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\Reservations\StoreRequest;
use App\Models\Listing;
use App\Models\User;
use App\Services\Model\UserReservationServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Http\JsonResponse;

class Store extends AuthController
{
    /**
     * @param StoreRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        /** @var Listing|null $oItem */
        $oItem = Listing::active()->where('id', $data['listing_id'])->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        // проверка email на уникальность в валидации
        // проверка телефона на уникальность
        $oUserService = (new UserServiceModel());
        if (!$oUserService->checkPhoneUnique($data['phone'])) {
            return responseCommon()->validationMessages(null, [
                'phone' => __('reservation.phone_unique'),
            ]);
        }
        // проверка свободности даты
        $oUserReservationServiceModel = (new UserReservationServiceModel($oItem));
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
        $oUser = $oUserReservationServiceModel->getUser();

        if (config('staymenity.reservation_sync_after_store')) {
            event(new ReservationSyncToEvent($oReservation));
        }

        return responseCommon()->apiDataSuccess([
            'id' => $oReservation->id,
            'token' => $this->getToken($oUser),
        ]);
    }
}
