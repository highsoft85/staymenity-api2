<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Calendar;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\UserTransformer;
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

        $dates = (new UserCalendarService($oUser))->dates();
        return responseCommon()->apiDataSuccess($dates);
    }
}
