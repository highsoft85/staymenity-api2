<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Payments\Stripe;

use App\Http\Controllers\Api\ApiController;
use App\Services\Environment;
use App\Services\Payment\Stripe\PaymentEphemeralService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Ephemeral extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        if (config('app.env') === Environment::DOCUMENTATION) {
            return responseCommon()->apiDataSuccess([]);
        }
        try {
            $ephemeral = (new PaymentEphemeralService())
                ->setUser($oUser)
                ->getEphemeralKey();
            return responseCommon()->apiDataObjectSuccess($ephemeral);
        } catch (\Exception $e) {
            return responseCommon()->apiErrorBadRequest([], $e->getMessage());
        }
    }
}
