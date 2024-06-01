<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Verifications\Identities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Verifications\Identities\StoreRequest;
use App\Models\User;
use App\Models\UserIdentity;
use App\Services\Environment;
use App\Services\Image\ImageType;
use App\Services\Model\UserIdentityVerificationServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class Store extends ApiController
{
    /**
     * @param StoreRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(StoreRequest $request)
    {
        $oUser = $this->authUser($request);
        $data = $request->validated();

        if (empty($oUser->email)) {
            return responseCommon()->apiErrorBadRequest([], __('validation.required', ['attribute' => 'Email']));
        }
        if (empty($oUser->first_name)) {
            return responseCommon()->apiErrorBadRequest([], __('validation.required', ['attribute' => 'First Name']));
        }
        if (empty($oUser->last_name)) {
            return responseCommon()->apiErrorBadRequest([], __('validation.required', ['attribute' => 'Last Name']));
        }

        $oIdentity = null;
        $result = transaction()->commitAction(function () use ($oUser, &$oIdentity) {
            $oIdentity = (new UserIdentityVerificationServiceModel($oUser))->create(UserIdentity::TYPE_DEFAULT);
        });
        if (!$result->isSuccess() || is_null($oIdentity)) {
            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
        }
//        $oService = (new UserIdentityVerificationServiceModel($oUser, $oIdentity));
//
//        if (isset($data['example_error']) && (int)$data['example_error'] === 1) {
//            $oService->setExampleError(true);
//        }
//
//        $files = $request->allFiles();
//        // главная
//        if (isset($files['image_front']) && !empty($files['image_front'])) {
//            $result = $oService->uploadImage($oIdentity, $oUser, 'image_front', $files['image_front']);
//            if ($result instanceof JsonResponse) {
//                //
//            }
//        }
//
//        // обратная
//        if (isset($files['image_back']) && !empty($files['image_back']) && in_array($oIdentity->type, [UserIdentity::TYPE_DRIVERS, UserIdentity::TYPE_ID])) {
//            $result = $oService->uploadImage($oIdentity, $oUser, 'image_back', $files['image_back']);
//            if ($result instanceof JsonResponse) {
//                //
//            }
//        }
//
//        // селфи
//        if (isset($files['image_selfie']) && !empty($files['image_selfie'])) {
//            $result = $oService->uploadImage($oIdentity, $oUser, 'image_selfie', $files['image_selfie']);
//            if ($result instanceof JsonResponse) {
//                //
//            }
//        }

        return responseCommon()->apiDataSuccess([
            'id' => $oIdentity->id,
        ], 'Your verification request is accepted, please check your email for the next steps.');
    }
}
