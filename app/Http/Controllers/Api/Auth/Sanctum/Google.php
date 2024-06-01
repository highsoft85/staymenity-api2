<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth\Sanctum;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Models\User;
use App\Services\Environment;
use App\Services\Socialite\FacebookAccountService;
use App\Services\Socialite\GoogleAccountService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;

class Google extends AuthController
{
    /**
     * @param Request $request
     * @param GoogleAccountService $service
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, GoogleAccountService $service)
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
        $oUser = $service->createOrGetUser(Socialite::with(GoogleAccountService::NAME)->stateless()->user(), $oUser);
        if (is_null($oUser)) {
            return responseCommon()->apiUnauthorized();
        }
        return responseCommon()->apiDataSuccess([
            'token' => $this->getToken($oUser),
        ], __('auth.login_success'));
    }
}
