<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Saves;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Destroy extends ApiController
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request, int $id)
    {
        $oUser = $this->authUser($request);

        $oItem = $oUser->saves()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $oItem->delete();
        return responseCommon()->apiSuccess();
    }
}
