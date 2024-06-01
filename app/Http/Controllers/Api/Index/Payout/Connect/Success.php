<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Index\Payout\Connect;

use App\Http\Requests\Api\Index\Payout\Connect\SuccessRequest;
use App\Models\User;
use App\Services\Model\UserPaymentCardServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Success
{
    /**
     * @param SuccessRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(SuccessRequest $request)
    {
        $token = $request->get('token');

        $result = transaction()->commitAction(function () use ($token) {
            $email = Crypt::decrypt($token);
            if (empty($email)) {
                throw new \Exception('Token is incorrect');
            }
            /** @var User|null $oUser */
            $oUser = User::where('email', $email)->first();
            if (is_null($oUser)) {
                throw new \Exception('User not found');
            }
            (new UserServiceModel($oUser))->setHasPayoutConnect();
            slackInfo('USER_ID: ' . $oUser->id, 'PAYOUT_CONNECT_SUCCESS');
        });
        if (!$result->isSuccess()) {
            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
        }
        return responseCommon()->apiSuccess([], 'Connect was successfully');
    }
}
