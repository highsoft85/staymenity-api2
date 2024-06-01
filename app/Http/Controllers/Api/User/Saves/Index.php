<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Saves;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Models\Listing;
use App\Models\UserSave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        $limit = $request->get('limit') ?? 4;
        /** @var LengthAwarePaginator $oResult */
        $oResult = $oUser->savesActive()->paginate($limit);
        $aItems = $oResult->values()->transform(function (UserSave $item) {
            return (new UserSaveTransformer())->transform($item);
        })->toArray();
        return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
    }
}
