<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Services\Environment;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HostController extends ApiController
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
    public function listings(Request $request)
    {
        $oUser = $this->authHost($request);
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
        $oUser = $this->authHost($request);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }

        return responseCommon()->apiDataSuccess([]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function payouts(Request $request)
    {
        $oUser = $this->authHost($request);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }

        return responseCommon()->apiDataSuccess([]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function reservations(Request $request)
    {
        $oUser = $this->authHost($request);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }

        return responseCommon()->apiDataSuccess([]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function reservationsUpcoming(Request $request)
    {
        $oUser = $this->authHost($request);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }

        return responseCommon()->apiDataSuccess([]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function reservationsPrevious(Request $request)
    {
        $oUser = $this->authHost($request);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }

        return responseCommon()->apiDataSuccess([]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function reservationsCancelled(Request $request)
    {
        $oUser = $this->authHost($request);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }

        return responseCommon()->apiDataSuccess([]);
    }
}
