<?php

declare(strict_types=1);

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Socialite\GoogleAccountService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialAuthGoogleController extends AuthController
{
    /**
     * Create a redirect method to google api.
     *
     * @return RedirectResponse
     */
    public function redirectToProvider()
    {
        //->scopes(GoogleAccountService::SCOPES)
        return Socialite::driver(GoogleAccountService::NAME)->stateless()->redirect();
    }

    /**
     * @param Request $request
     * @param GoogleAccountService $service
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(Request $request, GoogleAccountService $service)
    {
        $oUser = $service->createOrGetUser(Socialite::with(GoogleAccountService::NAME)->stateless()->user());
        if (!is_null($oUser)) {
            return responseCommon()->apiDataSuccess([
                'token' => $this->getToken($oUser),
            ], __('auth.login_success'));
        }
        return responseCommon()->success();
    }
}
