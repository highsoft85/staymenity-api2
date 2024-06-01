<?php

declare(strict_types=1);

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Services\Socialite\FacebookAccountService;
use App\Services\Socialite\GoogleAccountService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialAuthFacebookController extends AuthController
{
    /**
     * Create a redirect method to google api.
     *
     * @return RedirectResponse
     */
    public function redirectToProvider()
    {
        return Socialite::driver(FacebookAccountService::NAME)->stateless()->redirect();
    }

    /**
     * @param Request $request
     * @param FacebookAccountService $service
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(Request $request, FacebookAccountService $service)
    {
        $oUser = $service->createOrGetUser(Socialite::with(FacebookAccountService::NAME)->stateless()->user());
        if (!is_null($oUser)) {
            return responseCommon()->apiDataSuccess([
                'token' => $this->getToken($oUser),
            ], __('auth.login_success'));
        }
        return responseCommon()->success();
    }
}
