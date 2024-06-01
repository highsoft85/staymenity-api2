<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Chats\Messages;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Chats\Messages\StoreRequest;
use App\Http\Transformers\Api\DeviceTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Chat;
use App\Models\Device;
use App\Models\User;
use App\Services\Calendar\UserCalendarService;
use App\Services\Model\UserChatServiceModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Store extends ApiController
{
    /**
     * @param StoreRequest $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(StoreRequest $request, int $id)
    {
        $oUser = $this->authUser($request);
        $data = $request->validated();

        /** @var Chat|null $oChat */
        $oChat = $oUser->chatsActive()->where('id', $id)->first();
        if (is_null($oChat)) {
            return responseCommon()->apiNotFound();
        }
        (new UserChatServiceModel($oUser, $oChat))->message($data['message']);

        return responseCommon()->apiSuccess([]);
    }
}
