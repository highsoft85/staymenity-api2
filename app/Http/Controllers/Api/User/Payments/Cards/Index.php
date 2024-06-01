<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Payments\Cards;

use App\Http\Controllers\Api\ApiController;
use App\Services\Environment;
use App\Services\Payment\Stripe\PaymentMethodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Index extends ApiController
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

//        $oCards = $oUser->paymentCards()->active()->get();
//        $aItems = $oCards->transform(function (PaymentCard $item) {
//            return (new PaymentCardTransformer())->transform($item);
//        })->toArray();

        if (is_null($oUser->details)) {
            return responseCommon()->apiDataSuccess([]);
        }

        if (is_null($oUser->details->customerValue)) {
            return responseCommon()->apiDataSuccess([]);
        }

        $aCards = (new PaymentMethodService())
            ->setUser($oUser)
            ->getPaymentMethods();

        return responseCommon()->apiDataSuccess($aCards);
    }
}
