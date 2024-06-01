<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth\Password;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\Auth\Password\PhoneRequest;
use App\Http\Requests\Api\Auth\PhoneCodeRequest;
use App\Jobs\Auth\SendPhoneCodeJob;
use App\Models\User;
use App\Services\Verification\VerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class Phone extends AuthController
{
    /**
     * Пользователь нажимает сбросить пароль по телефону
     * Подтверждает свой номер
     *
     *
     * @param PhoneRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(PhoneRequest $request)
    {
        $data = $request->validated();

        if (!isset($data['phone']) || (int)$data['phone_verified'] === 0) {
            return responseCommon()->apiErrorBadRequest([], 'Phone must by verified');
        }
        // вытащить phone из юзера или из $data
        $phone = $this->getPhoneByDataSendVerify($data);
        if (!$this->checkPhone($phone)) {
            return responseCommon()->validationMessages(null, [
                'phone' => __('validation.phone', ['attribute' => 'phone']),
            ]);
        }
        /** @var User|null $oUser */
        $oUser = User::where('phone', $phone)->first();
        if (is_null($oUser)) {
            return responseCommon()->apiNotFound();
        }
        if (is_null($oUser->email)) {
            return responseCommon()->apiNotFound();
        }
//        $oVerificationService = (new VerificationService())
//            ->setPhone($phone)
//            ->resetConfirmation();
//
//        if (!$oVerificationService->canSend()) {
//            $seconds = $oVerificationService->getWaitSeconds();
//            return responseCommon()->apiError([], 'Please wait ' . $seconds . ' seconds for send new code.', 400);
//        }
//        $code = $oVerificationService->generate();
//
//        $oVerificationService->send();
//        if ($oVerificationService->isTesting($phone)) {
//            return responseCommon()->apiDataSuccess([
//                'code' => $code,
//                'reset_token' => $resetToken,
//            ], 'Success');
//        }
        return responseCommon()->apiDataSuccess([
            'email' => $oUser->email,
            'reset_token' => $oUser->emailToken,
        ], 'Success');
    }
}
