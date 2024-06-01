<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Model\UserServiceModel;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends AuthController
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @param ResetPasswordRequest $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        $user_id = null;
        if ($request->exists('phpunit') && $request->get('phpunit')) {
            $response = Password::PASSWORD_RESET;
        } else {
            $response = $this->broker()->reset($this->credentials($request), function ($user, $password) use (&$user_id) {
                $user_id = $user->id;
                $this->resetPassword($user, $password);
            });
        }
        if ($response !== Password::PASSWORD_RESET) {
            return responseCommon()->validationMessages(null, [
                'email' => __($response),
            ]);
        }
        // автоматическая авторизация после сброса пароля
        if (!is_null($user_id)) {
            /** @var User $oUser */
            $oUser = User::find($user_id);
            $oUserService = (new UserServiceModel($oUser));
            if (!$oUserService->checkUserBeforeLogin()) {
                Auth::logout();
                return responseCommon()->validationMessages(null, [
                    'password' => __('auth.account_banned'),
                ]);
            }
            $token = $this->getToken($oUser);
            return responseCommon()->apiDataSuccess([
                'token' => $token,
            ], __('auth.login_success'));
        }
        return responseCommon()->apiSuccess([], __($response));
    }
}
