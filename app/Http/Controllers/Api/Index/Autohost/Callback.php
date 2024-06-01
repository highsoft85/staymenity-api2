<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Index\Autohost;

use App\Http\Requests\Api\Index\Payout\Connect\SuccessRequest;
use App\Models\User;
use App\Services\Model\UserPaymentCardServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Callback
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        slackInfo($request->all(), 'AUTOHOST CALLBACK');
        return responseCommon()->apiSuccess([], 'Connect was successfully');
    }
}
