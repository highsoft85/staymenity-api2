<?php

declare(strict_types=1);

namespace App\Cmf\Project\UserIdentity;

use App\Models\UserIdentity;
use App\Notifications\User\Identity\UserIdentityVerificationStatusNotification;
use App\Services\Model\UserIdentityVerificationServiceModel;
use App\Services\Verification\VerificationAutohostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait UserIdentityCustomTrait
{
    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionCheckStatus(Request $request, int $id)
    {
        /** @var UserIdentity $oUserIdentity */
        $oUserIdentity = UserIdentity::find($id);

        $oUser = $oUserIdentity->user;

        //(new UserIdentityVerificationServiceModel($oUser, $oUserIdentity))->update();


        //$data = (new VerificationAutohostService())->checkStatus($oUserIdentity->autohost_reservation_id);
        //$data = (new VerificationAutohostService())->getReservation($oUserIdentity->autohost_reservation_id);
        //return responseCommon()->success([], 'Success');

        if ($oUserIdentity->isLocalKey()) {
            $result = transaction()->commitAction(function () use ($oUser, &$oUserIdentity) {
                $oUserIdentity = (new UserIdentityVerificationServiceModel($oUser, $oUserIdentity))->update();
            });
            if (!$result->isSuccess()) {
                return responseCommon()->error([], $result->getErrorMessage());
            }
            $oService = (new UserIdentityVerificationServiceModel($oUser, $oUserIdentity));
            $oService->uploadImageByExisting($oUserIdentity, 'image_front');
            $oService->uploadImageByExisting($oUserIdentity, 'image_back');
            $oService->uploadImageByExisting($oUserIdentity, 'image_selfie');
            $oUserIdentity->refresh();
        }

        $oService = (new UserIdentityVerificationServiceModel($oUser, $oUserIdentity));

        $result = transaction()->commitAction(function () use ($oService, $oUserIdentity, $oUser) {
            $oService->commonCheckStatusAndSaveResults();
            $oUserIdentity->refresh();
            if ($oUserIdentity->status !== UserIdentity::STATUS_PENDING) {
                $oUser->notify(new UserIdentityVerificationStatusNotification($oUserIdentity));
            }
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
    public function actionForceVerified(Request $request, int $id)
    {
        /** @var UserIdentity $oUserIdentity */
        $oUserIdentity = UserIdentity::find($id);

        $oUser = $oUserIdentity->user;

        $oService = (new UserIdentityVerificationServiceModel($oUser, $oUserIdentity));

        $oService->statusSuccess();
        $oUserIdentity->refresh();
        if ($oUserIdentity->status !== UserIdentity::STATUS_PENDING) {
            $oUser->notify(new UserIdentityVerificationStatusNotification($oUserIdentity));
        }
        return responseCommon()->success([], 'Success');
    }
}
