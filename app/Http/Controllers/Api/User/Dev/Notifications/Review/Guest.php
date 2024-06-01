<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Dev\Notifications\Review;

use App\Http\Controllers\Api\ApiController;
use App\Models\Reservation;
use App\Notifications\User\LeaveReviewNotification;
use App\Notifications\User\TestNotification;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserReservationServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Guest extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        if (envIsDocumentation()) {
            return responseCommon()->apiSuccess();
        }

        $oHost = factoryModel()->factoryHost();
        $oListing = factoryModel()->factoryUserListingActive($oHost);

        $oReservation = factoryModel()->factoryReservationListingFromUser($oListing, $oUser, [
            'start_at' => now()->subDay()->startOfDay()->addHours(3)->format(UserReservationServiceModel::DATE_FORMAT),
            'finish_at' => now()->subDay()->startOfDay()->addHours(4)->endOfHour()->format(UserReservationServiceModel::DATE_FORMAT),
            'status' => Reservation::STATUS_ACCEPTED,
        ]);

        $oUser->notify(new LeaveReviewNotification($oReservation, ReviewServiceModel::TYPE_TO_GUEST, $oUser));

        return responseCommon()->apiSuccess();
    }
}
