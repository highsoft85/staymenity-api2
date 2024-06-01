<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Payouts\Stripe;

use App\Http\Controllers\Api\ApiController;
use App\Services\Environment;
use App\Services\Model\UserServiceModel;
use App\Services\Payment\Stripe\PaymentAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Connect extends ApiController
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

        if (empty($oUser->phone)) {
            return responseCommon()->apiErrorBadRequest([], __('payout.failed_phone'));
        }
        if (empty($oUser->email)) {
            return responseCommon()->apiErrorBadRequest([], __('payout.failed_email'));
        }

        if (is_null($oUser->details->stripeAccountValue)) {
            // создание акканта
            $result = transaction()->commitAction(function () use ($oUser) {
                $oService = (new PaymentAccountService())
                    ->setUser($oUser);

                $account = $oService->create();

                if (is_null($account)) {
                    throw new \Exception($oService->getMessage());
                }

                $oService = (new PaymentAccountService())
                    ->setUser($oUser);

                $link = $oService->getExpressLink();

                if (is_null($link)) {
                    throw new \Exception($oService->getMessage());
                }
                return $link->url;
            });
            if (!$result->isSuccess()) {
                return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
            }
            return responseCommon()->apiDataSuccess([
                'redirect' => $result->getReturn(),
            ]);
        } else {
            // получение продолжить регистрацию
            $result = transaction()->commitAction(function () use ($oUser) {
                $oService = (new PaymentAccountService())
                    ->setUser($oUser);

                // если
                if (!$oService->accountIsEnabled()) {
                    $link = $oService->getExpressLink();
                    return $link->url;
                }
                (new UserServiceModel($oUser))->setHasPayoutConnect();
                $link = $oService->getLoginLink();
                return $link->url;
            });
            if (!$result->isSuccess()) {
                return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
            }
            return responseCommon()->apiDataSuccess([
                'redirect' => $result->getReturn(),
            ]);
        }
    }
}
