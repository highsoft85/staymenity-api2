<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Reservations\Review;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\User\Reservations\Review\StoreRequest;
use App\Http\Requests\Api\User\Reservations\UpdateRequest;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\UserReservationServiceModel;
use Illuminate\Http\JsonResponse;
use Rennokki\Rating\Contracts\Rating;
use App\Services\Transaction\Transaction;
use Rennokki\Rating\Models\RaterModel;

class Store extends ApiController
{
    /**
     * @param StoreRequest $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(StoreRequest $request, int $id)
    {
        $oUser = $this->authUser($request);
        $data = $request->validated();

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
            if ($oReservationService->hasReviewFromHost()) {
                return responseCommon()->apiErrorBadRequest([], 'Review is existing by this reservation');
            }
            if (!$oReservationService->canLeaveReview($oUser)) {
                return responseCommon()->apiErrorBadRequest([], 'Access denied');
            }
            $result = $oReservationService->setReviewByHost($oUser, $oGuest, $data);
            if (!$result->isSuccess()) {
                return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
            } else {
                (new ListingServiceModel($oListing))->updateRating();
                return responseCommon()->apiSuccess();
            }
        }
        // если не юзера листинг
        // и бронь на юзера
        // то отзыв листингу
        if ($oListing->user_id !== $oUser->id && $oReservation->user_id === $oUser->id) {
            if ($oReservationService->hasReviewFromGuest()) {
                return responseCommon()->apiErrorBadRequest([], 'Review is existing by this reservation');
            }
            if (!$oReservationService->canLeaveReview($oUser)) {
                return responseCommon()->apiErrorBadRequest([], 'Access denied');
            }
            $result = $oReservationService->setReviewByGuest($oUser, $oListing, $data);
            if (!$result->isSuccess()) {
                return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
            } else {
                (new ListingServiceModel($oListing))->updateRating();
                return responseCommon()->apiSuccess();
            }
        }
        return responseCommon()->apiNotFound();
    }
}
