<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Services\Environment;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GuestController extends ApiController
{
    use Helpers;

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function dashboard(Request $request)
    {
        $oUser = $this->authGuest($request);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }

        return responseCommon()->apiDataSuccess([]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function bookings(Request $request)
    {
        $oUser = $this->authGuest($request);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }

        return responseCommon()->apiDataSuccess([]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function messages(Request $request)
    {
        $oUser = $this->authGuest($request);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }

        return responseCommon()->apiDataSuccess([]);
    }
}
