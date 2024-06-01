<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Host;

use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Show
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        /** @var User|null $oUser */
        $oUser = User::active()->where('id', $id)->first();
        if (is_null($oUser)) {
            return responseCommon()->apiNotFound();
        }
        if (!$oUser->hasAnyRole([User::ROLE_HOST])) {
            return responseCommon()->apiNotFound();
        }
        $oUser->load('reviewsActiveOrdered', 'modelImages');
        $aItem = (new UserTransformer())->transformDetail($oUser, User::ROLE_HOST);
        visit()->user($oUser);
        return responseCommon()->apiDataSuccess($aItem);
    }
}
