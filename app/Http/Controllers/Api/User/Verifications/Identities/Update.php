<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Verifications\Identities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Verifications\Identities\UpdateRequest;
use App\Models\User;
use App\Models\UserIdentity;
use App\Services\Image\ImageType;
use App\Services\Model\UserIdentityVerificationServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class Update extends ApiController
{
    /**
     * @param UpdateRequest $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(UpdateRequest $request, int $id)
    {
        $oUser = $this->authUser($request);

        /** @var UserIdentity $oIdentity */
        $oIdentity = UserIdentity::find($id);

        $oService = (new UserIdentityVerificationServiceModel($oUser, $oIdentity));
        $files = $request->allFiles();
        // главная
        if (isset($files['image_front']) && !empty($files['image_front'])) {
            $result = $oService->uploadImage($oIdentity, $oUser, 'image_front', $files['image_front']);
            if ($result instanceof JsonResponse) {
                return $result;
            }
        }

        // обратная
        if (isset($files['image_back']) && !empty($files['image_back']) && $oIdentity->type === UserIdentity::TYPE_DRIVERS) {
            $result = $oService->uploadImage($oIdentity, $oUser, 'image_back', $files['image_back']);
            if ($result instanceof JsonResponse) {
                return $result;
            }
        }

        // селфи
        if (isset($files['image_selfie']) && !empty($files['image_selfie'])) {
            $result = $oService->uploadImage($oIdentity, $oUser, 'image_selfie', $files['image_selfie']);
            if ($result instanceof JsonResponse) {
                return $result;
            }
        }
        //$oService->commonCheckStatusAndSaveResults();

        return responseCommon()->apiDataSuccess([
            'id' => $oIdentity->id,
        ]);
    }
}
