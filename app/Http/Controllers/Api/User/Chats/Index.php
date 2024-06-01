<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Chats;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Devices\StoreRequest;
use App\Http\Transformers\Api\ChatTransformer;
use App\Http\Transformers\Api\DeviceTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Chat;
use App\Models\Device;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Calendar\UserCalendarService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Index extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        $aItems = [];
        if ($oUser->current_role === User::ROLE_GUEST) {
            //$aId = $oUser->reservations()->pluck('id')->toArray();
            $oChats = $oUser->chatsActive()->where('creator_id', $oUser->id)->ordered()->get();
            $aItems = $oChats->transform(function (Chat $item) {
                return (new ChatTransformer())->transformForGuest($item);
            })->toArray();
        }
        if ($oUser->current_role === User::ROLE_HOST) {
            $aId = $oUser->listingsActive()->pluck('id')->toArray();
            //$aId = Reservation::whereIn('listing_id', $aId)->pluck('id')->toArray();
            $oChats = $oUser->chatsActive()->whereIn('listing_id', $aId)->ordered()->get();
            $aItems = $oChats->transform(function (Chat $item) {
                return (new ChatTransformer())->transformForHost($item);
            })->toArray();
        }
        return responseCommon()->apiDataSuccess($aItems);
    }
}
