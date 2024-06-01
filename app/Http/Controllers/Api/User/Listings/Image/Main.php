<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Listings\Image;

use App\Http\Controllers\Api\ApiController;
use App\Models\Listing;
use App\Models\User;
use App\Services\Image\ImageService;
use App\Services\Image\ImageType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Main extends ApiController
{
    /**
     * @param Request $request
     * @param int $id
     * @param int $image_id
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, int $id, int $image_id)
    {
        $oUser = $this->authUser($request);
        /** @var Listing|null $oItem */
        $oItem = $oUser->listingsActive()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        if (!$oUser->checkListingAccess($oItem)) {
            return responseCommon()->apiErrorBadRequest([], 'Access denied');
        }
        $oImage = $oItem->modelImages()->where('id', $image_id)->first();
        if (is_null($oImage)) {
            return responseCommon()->apiNotFound();
        }
        $type = ImageType::MODEL;
        $options = (new \App\Cmf\Project\Listing\ListingController())->image[$type];
        $oImageService = (new ImageService());
        $oImageService->main($oItem, $oImage, $options, $type);
        return responseCommon()->apiSuccess();
    }
}
