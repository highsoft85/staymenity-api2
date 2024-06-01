<?php

declare(strict_types=1);

namespace App\Cmf\Project\Reservation;

use App\Models\Reservation;
use App\Notifications\User\Reservation\ReservationPayoutNotification;
use App\Notifications\User\Reservation\ReservationTransferNotification;
use App\Services\Model\ReservationServiceModel;
use Illuminate\Http\Request;

trait ReservationCustomTrait
{
    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionCancelReservation(Request $request, int $id)
    {
        /** @var Reservation $oReservation */
        $oReservation = Reservation::find($id);

        $oService = (new ReservationServiceModel($oReservation))
            ->setCancelledType(Reservation::CANCELLED_TYPE_BY_ADMIN);

        $result = transaction()->commitAction(function () use ($oService) {
            $oService->forceSetCancel();
        });
        if (!$result->isSuccess()) {
            return responseCommon()->error([], $result->getErrorMessage());
        }
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionTransferReservation(Request $request, int $id)
    {
        /** @var Reservation $oReservation */
        $oReservation = Reservation::find($id);

        $oService = (new ReservationServiceModel($oReservation));

        $result = transaction()->commitAction(function () use ($oService, $oReservation) {
            $oService->makeTransfer();
            $oHost = $oReservation->listing->user;
            $oHost->notify(new ReservationTransferNotification($oReservation));
        });
        if (!$result->isSuccess()) {
            return responseCommon()->error([], $result->getErrorMessage());
        }
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionPayoutReservation(Request $request, int $id)
    {
        /** @var Reservation $oReservation */
        $oReservation = Reservation::find($id);

        $oService = (new ReservationServiceModel($oReservation));

        $result = transaction()->commitAction(function () use ($oService, $oReservation) {
            $oService->makePayout();
            $oHost = $oReservation->listing->user;
            $oHost->notify(new ReservationPayoutNotification($oReservation));
        });
        if (!$result->isSuccess()) {
            return responseCommon()->error([], $result->getErrorMessage());
        }
        return responseCommon()->success([], 'Success');
    }
}
