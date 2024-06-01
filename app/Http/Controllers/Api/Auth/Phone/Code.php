<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth\Phone;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\Auth\PhoneCodeRequest;
use App\Jobs\Auth\SendPhoneCodeJob;
use App\Models\User;
use App\Services\Verification\VerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class Code extends AuthController
{
    /**
     * @param PhoneCodeRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(PhoneCodeRequest $request)
    {
        $data = $request->validated();
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
        $oVerificationService->setPhone($phone);

        // вставить в сервис, будет поиск с user_id
        if (!is_null($oUser)) {
            $oVerificationService->setUser($oUser);
        }

        if (!$oVerificationService->canSend()) {
            $seconds = $oVerificationService->getWaitSeconds();
            return responseCommon()->validationMessages(null, [
                'phone' => 'Please wait ' . $seconds . ' seconds for send new code.',
            ]);
        }
        $result = transaction()->commitAction(function () use ($oVerificationService) {
            $code = $oVerificationService->generate();
            $oVerificationService->send();
            return $code;
        });
        if (!$result->isSuccess()) {
            return responseCommon()->validationMessages(null, [
                'phone' => $result->getErrorMessage(),
            ]);
        }
        $code = $result->getData();
        if ($oVerificationService->isTesting($phone)) {
            return responseCommon()->apiDataSuccess([
                'code' => $code,
            ], 'Success');
        }
        return responseCommon()->apiSuccess([], 'Success');
    }
}
