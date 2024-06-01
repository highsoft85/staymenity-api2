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

class Show extends ApiController
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        $oUser = $this->authUser($request);

        /** @var UserSave|null $oItem */
        $oItem = $oUser->saves()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $aItem = (new UserSaveTransformer())->transformDetail($oItem);
        return responseCommon()->apiDataSuccess($aItem);
    }
}
