<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Image;

use App\Models\Listing;
use App\Models\User;
use App\Services\Image\ImageService;
use App\Services\Image\ImageType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Destroy
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        /** @var User $oUser */
        $oUser = $request->user();
        $oImage = $oUser->modelImages()->first();
        if (is_null($oImage)) {
            return responseCommon()->apiNotFound();
        }
        $type = ImageType::MODEL;
        $options = (new \App\Cmf\Project\Listing\ListingController())->image[$type];
        $oImageService = (new ImageService());
        $oImageService->delete($oUser, $oImage, $options, $type);
        return responseCommon()->apiSuccess();
    }
}
