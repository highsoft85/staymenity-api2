<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Notifications;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Clear extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        $oItems = $oUser->notifications()->get();
        foreach ($oItems as $oItem) {
            $oItem->delete();
        }
        return responseCommon()->apiSuccess();
    }
}
