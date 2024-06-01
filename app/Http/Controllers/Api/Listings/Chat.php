<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Listings;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\ChatTransformer;
use App\Models\Listing;
use App\Services\Model\UserChatServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Chat extends ApiController
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
        $oItem = Listing::active()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }

        $oChat = (new UserChatServiceModel($oUser))->createByListing($oItem);
        $aChat = (new ChatTransformer())->transformForGuest($oChat);
        return responseCommon()->apiDataSuccess($aChat);
    }
}
