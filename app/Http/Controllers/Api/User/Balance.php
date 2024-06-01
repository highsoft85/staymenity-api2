<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\UserTransformer;
use App\Services\Payment\Stripe\PaymentBalanceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Balance extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        $data = [
            'available' => 0,
            'pending' => 0,
        ];

        if ($oUser->hasPayoutConnect() || envIsTesting()) {
            $balance = (new PaymentBalanceService())
                ->setUser($oUser)
                ->get();

            $data = [
                'available' => $this->amountWithoutCents($balance->available[0]->amount),
                'pending' => $this->amountWithoutCents($balance->pending[0]->amount),
            ];
            return responseCommon()->apiDataSuccess($data);
        }
        return responseCommon()->apiDataSuccess($data);
    }

    /**
     * @param int $amount
     * @return float
     */
    private function amountWithoutCents(int $amount)
    {
        return (float)($amount / 100);
    }
}
