<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Listings;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Listings\UpdateRequest;
use App\Models\Listing;
use App\Models\User;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\ListingUploadServiceModel;
use Illuminate\Http\JsonResponse;

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
        /** @var Listing|null $oListing */
        $oListing = $oUser->listings()->where('id', $id)->first();
        if (is_null($oListing)) {
            return responseCommon()->apiNotFound();
        }
        if (!$oUser->checkListingAccess($oListing)) {
            return responseCommon()->apiAccessDenied();
        }
        $data = $request->validated();
        $oService = (new ListingServiceModel($oListing));
        $oListing = $oService->update($data);

        // проверка тайм слотов
        if (isset($data['times'])) {
            try {
                $oService->saveTimes($data['times']);
            } catch (\Exception $e) {
                return responseCommon()->validationMessages(null, [
                    'times' => $e->getMessage(),
                ]);
            }
        }

        $files = $request->allFiles();
        if (isset($files['images']) && !empty($files['images'])) {
            $oUploadService = (new ListingUploadServiceModel($oListing));
            if (!$oUploadService->uploadCheckValidation($data, $files)) {
                return responseCommon()->validationMessages(null, [
                    'images' => $oUploadService->getMessage(),
                ]);
            }
            $oUploadService->upload($data, $files);
        }
        return responseCommon()->apiDataSuccess([]);
    }
}
