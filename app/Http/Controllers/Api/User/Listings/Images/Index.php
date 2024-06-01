<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Listings\Images;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\ImageTransformer;
use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Image;
use App\Models\Listing;
use App\Services\Image\ImageSize;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends ApiController
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        $oUser = $this->authUser($request);
        /** @var Listing|null $oItem */
        $oItem = $oUser->listingsActive()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $aImages = (new ImageTransformer())->transformListingImages($oItem, ImageSize::SQUARE_XL);
        return responseCommon()->apiDataSuccess($aImages);
    }
}
