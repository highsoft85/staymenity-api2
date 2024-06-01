<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Notifications;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Destroy extends ApiController
{
    /**
     * @param Request $request
     * @param string $id
     * @return array|JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request, string $id)
    {
        $oUser = $this->authUser($request);

        $oItem = $oUser->notifications()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $oItem->delete();
        return responseCommon()->apiSuccess();
    }
}
