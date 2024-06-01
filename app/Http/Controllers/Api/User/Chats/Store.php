<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Chats;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Chats\StoreRequest;
use App\Models\Reservation;
use App\Services\Model\UserChatServiceModel;
use Illuminate\Http\JsonResponse;

class Store extends ApiController
{
    /**
     * @param StoreRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(StoreRequest $request)
    {
        $oUser = $this->authUser($request);
        if (cmfIsAdminVisual($request)) {
            $data = $request->query->all();
        } else {
            $data = $request->validated();
        }

        /** @var Reservation|null $oReservation */
        $oReservation = Reservation::find($data['reservation_id']);
        if (is_null($oReservation)) {
            return responseCommon()->apiNotFound();
        }
//        if (!is_null($oReservation->chatActive)) {
//            return responseCommon()->apiError([], 'Chat is existing');
//        }
        $oChat = (new UserChatServiceModel($oUser))->createByReservation($oReservation);

        return responseCommon()->apiDataSuccess([
            'id' => $oChat->id,
        ]);
    }
}
