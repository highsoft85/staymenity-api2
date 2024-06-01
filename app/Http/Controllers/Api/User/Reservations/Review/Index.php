<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Reservations\Review;

use App\Http\Controllers\Api\ApiController;
use App\Models\Listing;
use App\Models\Reservation;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\ReviewServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Index extends ApiController
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
        $oReservation = Reservation::find($id);
        if (is_null($oReservation)) {
            return responseCommon()->apiNotFound();
        }
        /** @var Listing|null $oListing */
        $oListing = $oReservation->listing;
        if (is_null($oListing)) {
            return responseCommon()->apiNotFound();
        }
        $oHost = $oListing->user;
        if (is_null($oHost)) {
            return responseCommon()->apiNotFound();
        }
        $oGuest = $oReservation->user;
        if (is_null($oGuest)) {
            return responseCommon()->apiNotFound();
        }
        $oReservationService = (new ReservationServiceModel($oReservation));

        // если юзера листинг, то отзыв гостю
        if ($oListing->user_id === $oUser->id) {
            if (!$oReservationService->canLeaveReview($oUser)) {
                return responseCommon()->apiErrorBadRequest([], 'Access denied');
            }
            return responseCommon()->apiDataSuccess([
                'title' => 'Review for ' . $oGuest->first_name,
                'description' => (new ReviewServiceModel())->getMessageByType($oGuest, ReviewServiceModel::TYPE_TO_HOST),
            ]);
        }
        // если не юзера листинг
        // и бронь на юзера
        // то отзыв листингу
        if ($oListing->user_id !== $oUser->id && $oReservation->user_id === $oUser->id) {
            if (!$oReservationService->canLeaveReview($oUser)) {
                return responseCommon()->apiErrorBadRequest([], 'Access denied');
            }
            return responseCommon()->apiDataSuccess([
                'title' => 'Review for ' . $oHost->first_name,
                'description' => (new ReviewServiceModel())->getMessageByType($oHost, ReviewServiceModel::TYPE_TO_GUEST),
            ]);
        }
        return responseCommon()->apiNotFound();
    }
}
