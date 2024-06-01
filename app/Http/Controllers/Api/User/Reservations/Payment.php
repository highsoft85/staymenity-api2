<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Reservations;

use App\Events\Reservation\ReservationSuccessEvent;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Reservations\PaymentRequest;
use App\Models\Reservation;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\UserChatServiceModel;
use Illuminate\Http\JsonResponse;

class Payment extends ApiController
{
    /**
     * @param PaymentRequest $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(PaymentRequest $request, int $id)
    {
        $oUser = $this->authUser($request);
        if (cmfIsAdminVisual($request)) {
            $data = $request->query->all();
        } else {
            $data = $request->validated();
        }

        /** @var Reservation|null $oReservation */
        $oReservation = Reservation::find($id);
        if (is_null($oReservation)) {
            return responseCommon()->apiNotFound();
        }
        $oHost = $oReservation->listing->user;
        if (is_null($oHost)) {
            return responseCommon()->apiNotFound();
        }

        // проверки листинга, хоста и баланса
        $oReservationService = (new ReservationServiceModel($oReservation));
        if (!$oReservationService->checkBeforePayment()) {
            return responseCommon()->apiError([], $oReservationService->getMessage());
        }

        $oCard = null;
        /** @var \App\Models\Payment $oPayment */
        $oPayment = null;
        //$token = $data['token_id'] ?? null;
        $method = $data['payment_method_id'] ?? null;
//        // по токену
//        if (!is_null($token)) {
//            // совершение платежа
//            $result = transaction()->commitAction(function () use ($oReservationService, $oUser, $oHost, $token) {
//                $oReservationService->paymentByToken($oUser, $oHost, $token);
//            });
//            if (!$result->isSuccess()) {
//                return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
//            }
//        }
        // по payment_method
        if (!is_null($method)) {
            // совершение платежа
            $result = transaction()->commitAction(function () use ($oReservationService, $oUser, $oHost, $method) {
                $oReservationService->paymentByMethod($oUser, $oHost, $method);
            });
            if (!$result->isSuccess()) {
                return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
            }
            if (!is_null($oReservation->listing->hostfully)) {
                event(new ReservationSuccessEvent($oUser, $oReservation));
            }
        }
        $oReservation->refresh();
        $oPayment = $oReservation->payment;
        if (is_null($oPayment)) {
            return responseCommon()->apiErrorBadRequest([], 'Empty payment');
        }

        // создание чата
        $result = transaction()->commitAction(function () use ($oUser, $oReservation) {
            (new UserChatServiceModel($oUser))->createByReservation($oReservation);
        });
        if (!$result->isSuccess()) {
            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
        }
        return responseCommon()->apiDataSuccess([
            //'payment_provider_id' => $oPayment->provider_payment_id,
        ], 'Payment successful');
    }
}
