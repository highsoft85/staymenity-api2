<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth\Socialite;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\Auth\Socialite\SocialiteRequest;
use App\Models\User;
use App\Services\Environment;
use App\Services\Socialite\FacebookAccountService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;

class Facebook extends AuthController
{
    /**
     * @param SocialiteRequest $request
     * @param FacebookAccountService $service
     * @return array|JsonResponse
     */
    public function __invoke(SocialiteRequest $request, FacebookAccountService $service)
    {
        if (config('app.env') === Environment::DOCUMENTATION) {
            return responseCommon()->apiDataSuccess([
                'token' => '{token}',
            ], __('auth.login_success'));
        }
        slackInfo(FacebookAccountService::NAME);
        slackInfo($request->all());
        $oUser = null;
        if ($request->exists('user_id')) {
            $oUser = User::find($request->get('user_id'));
        }
        $token = $request->get('access_token');
        $oUser = $service->createOrGetUser(Socialite::with(FacebookAccountService::NAME)->userFromToken($token), $oUser);
        if (is_null($oUser)) {
            if (!is_null($service->errorMessage)) {
                return responseCommon()->apiError([], $service->errorMessage, 422);
            }
            return responseCommon()->apiUnauthorized();
        }
        slackInfo(['user_id' => $oUser->id]);
        $service->setRoleAfterAuth($oUser);
        return responseCommon()->apiDataSuccess([
            'token' => $this->getToken($oUser),
        ], __('auth.login_success'));
    }
}
