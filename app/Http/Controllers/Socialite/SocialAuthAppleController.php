<?php

declare(strict_types=1);

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Socialite\AppleAccountService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialAuthAppleController extends AuthController
{
    /**
     * Create a redirect method to google api.
     *
     * @return RedirectResponse
     */
    public function redirectToProvider()
    {
        return Socialite::driver(AppleAccountService::NAME)->redirect();
    }

    /**
     * @param Request $request
     * @param AppleAccountService $service
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(Request $request, AppleAccountService $service)
    {
        $oUser = $service->createOrGetUser(Socialite::with(AppleAccountService::NAME)->user());
        if (!is_null($oUser)) {
            return responseCommon()->apiDataSuccess([
                'token' => $this->getToken($oUser),
            ], __('auth.login_success'));
        }
        return responseCommon()->success();
    }
}
