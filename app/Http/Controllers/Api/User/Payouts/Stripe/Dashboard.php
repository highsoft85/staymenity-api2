<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Payouts\Stripe;

use App\Http\Controllers\Api\ApiController;
use App\Services\Environment;
use App\Services\Payment\Stripe\PaymentAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Dashboard extends ApiController
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
        if ($oUser->hasPayoutConnect() && !is_null($oUser->details->stripeAccountValue)) {
            // получение ссылки
            $result = transaction()->commitAction(function () use ($oUser) {
                $link = (new PaymentAccountService())
                    ->setUser($oUser)
                    ->getLoginLink();
                return $link->url;
            });
            if (!$result->isSuccess()) {
                return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
            }
            return responseCommon()->apiDataSuccess([
                'redirect' => $result->getReturn(),
            ]);
        }
        return responseCommon()->apiErrorBadRequest([], 'Account not connected');
    }
}
