<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth\Phone;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\Auth\PhoneVerifyRequest;
use App\Http\Requests\Api\Auth\PhoneCodeRequest;
use App\Models\PersonalVerificationCode;
use App\Models\User;
use App\Services\Environment;
use App\Services\Model\UserServiceModel;
use App\Services\Notification\Nexmo\NexmoSendNotification;
use App\Services\Verification\VerificationService;
use Illuminate\Http\JsonResponse;

class Verify extends AuthController
{
    /**
     * @param PhoneVerifyRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(PhoneVerifyRequest $request)
    {
        $data = $request->all();
        $code = $data['code'];

        $oUser = null;
        if (isset($data['user_id'])) {
            /** @var User $oUser */
            $oUser = User::find($data['user_id']);
        }
        // вытащить phone из юзера или из $data
        $phone = $this->getPhoneByDataSendVerify($data);
        if (!$this->checkPhone($phone)) {
            return responseCommon()->validationMessages(null, [
                'phone' => __('validation.phone', ['attribute' => 'phone']),
            ]);
        }
        $oVerificationService = (new VerificationService());
        if (isset($data['type'])) {
            $oVerificationService->setType($data['type']);
        } else {
            $oVerificationService->registration();
        }

        // вставить в сервис, будет поиск с user_id
        if (!is_null($oUser)) {
            $oVerificationService->setUser($oUser);
        }

        if ($oVerificationService->isLogin()) {
            $oUser = User::where('phone', $phone)->first();
            if (is_null($oUser)) {
                return responseCommon()->apiErrorBadRequest([], 'Account not found');
            }
            if (\Illuminate\Support\Facades\Auth::check()) {
                \Illuminate\Support\Facades\Auth::logout();
            }
            $oUserService = (new UserServiceModel($oUser));
            if (!$oUserService->checkUserBeforeLogin()) {
                return responseCommon()->apiAccessDenied();
            }
        }

        $oVerification = $oVerificationService->get($phone, $code);
        if (is_null($oVerification)) {
            return responseCommon()->apiError([], __('validation.code_invalid', ['attribute' => 'code']), 400);
        }
        if ($oVerification->isExpired()) {
            return responseCommon()->apiError([], 'Verification code is expired. Please try again.', 400);
        }
        if ($oVerification->isVerified()) {
            return responseCommon()->apiError([], 'Verified', 400);
        }
        $oVerification->update([
            'verified_at' => now(),
        ]);
        if (!is_null($oUser)) {
//            $oUser->update([
//                'phone_verified_at' => now(),
//            ]);
        }
        if ($oVerificationService->isLogin()) {
            if ($request->exists('role') && !empty($request->get('role'))) {
                (new UserServiceModel($oUser))->setCurrentRole($request->get('role'));
            }
            $token = $this->getToken($oUser);
            return responseCommon()->apiDataSuccess([
                'token' => $token,
            ], __('auth.login_success'));
        }
        return responseCommon()->apiSuccess([], 'Success');
    }
}
