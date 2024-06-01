<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth\Socialite;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\Auth\Socialite\SocialiteRequest;
use App\Models\User;
use App\Services\Environment;
use App\Services\Socialite\FacebookAccountService;
use App\Services\Socialite\GoogleAccountService;
use App\Services\Socialite\MockAccountService;
use App\Services\Socialite\MockSecondAccountService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;

class MockSecond extends AuthController
{
    /**
     * @param SocialiteRequest $request
     * @param MockSecondAccountService $service
     * @return array|JsonResponse
     */
    public function __invoke(SocialiteRequest $request, MockSecondAccountService $service)
    {
        if (config('app.env') === Environment::DOCUMENTATION) {
            return responseCommon()->apiDataSuccess([
                'token' => '{token}',
            ], __('auth.login_success'));
        }
        $oUser = null;
        if ($request->exists('user_id')) {
            $oUser = User::find($request->get('user_id'));
        }
        $oUser = $service->createOrGetUser($request->all(), $oUser);
        if (is_null($oUser)) {
            if (!is_null($service->errorMessage)) {
                return responseCommon()->apiError([], $service->errorMessage, 422);
            }
            return responseCommon()->apiUnauthorized();
        }
        $service->setRoleAfterAuth($oUser);
        return responseCommon()->apiDataSuccess([
            'token' => $this->getToken($oUser),
        ], __('auth.login_success'));
    }
}
