<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Chats;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Devices\StoreRequest;
use App\Http\Transformers\Api\DeviceTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Chat;
use App\Models\Device;
use App\Services\Calendar\UserCalendarService;
use App\Services\Model\UserChatServiceModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Destroy extends ApiController
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        $oUser = $this->authUser($request);

        /** @var Chat|null $oChat */
        $oChat = $oUser->chatsActive()->where('id', $id)->first();
        if (is_null($oChat)) {
            return responseCommon()->apiNotFound();
        }
        // никто не может удалить чат
        //return responseCommon()->apiAccessDenied();
        // только хост может удалить чат
        if ($oChat->owner_id !== $oUser->id) {
            return responseCommon()->apiErrorBadRequest([], 'Access denied');
        }
        (new UserChatServiceModel($oUser, $oChat))->delete();

        return responseCommon()->apiSuccess([]);
    }
}
