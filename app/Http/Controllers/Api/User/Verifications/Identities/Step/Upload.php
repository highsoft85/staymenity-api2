<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Verifications\Identities\Step;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Verifications\Identities\Step\UploadRequest;
use App\Http\Requests\Api\User\Verifications\Identities\UpdateRequest;
use App\Models\User;
use App\Models\UserIdentity;
use App\Services\Image\ImageType;
use App\Services\Model\UserIdentityVerificationServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class Upload extends ApiController
{
    /**
     * @param UploadRequest $request
     * @param int $id
     * @param string $step
     * @return array|JsonResponse
     */
    public function __invoke(UploadRequest $request, int $id, string $step)
    {
        $oUser = $this->authUser($request);

        /** @var UserIdentity $oIdentity */
        $oIdentity = UserIdentity::find($id);

        $oService = (new UserIdentityVerificationServiceModel($oUser, $oIdentity));
        $files = $request->allFiles();
        // главная
        if (isset($files['image']) && !empty($files['image'])) {
            $key = 'image_' . $step;
            $result = $oService->uploadImage($oIdentity, $oUser, $key, $files['image']);
            if ($result instanceof JsonResponse) {
                return $result;
            }
        }
        $oService->commonCheckStatusAndSaveResults();
        return responseCommon()->apiDataSuccess([], 'Success');
    }
}
