<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use App\Services\Model\UserServiceModel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends AuthController
{
    use AuthenticatesUsers;

    /**
     * @param LoginRequest $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            //$this->sendLockoutResponse($request);
            return responseCommon()->validationMessages(null, [
                'email' => __('auth.throttle'),
            ]);
        }
        if ($request->has('phpunit')) {
            $this->clearLoginAttempts($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        $this->incrementLoginAttempts($request);

        return responseCommon()->validationMessages(null, [
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function sendLoginResponse(Request $request)
    {
        return $this->authenticated($request, $this->guard()->user());
    }

    /**
     * @param Request $request
     * @param User|Authenticatable $oUser
     * @return array|\Illuminate\Http\JsonResponse
     */
    protected function authenticated(Request $request, $oUser)
    {
        if ($request->exists('role') && !empty($request->get('role'))) {
            (new UserServiceModel($oUser))->setCurrentRole($request->get('role'));
        }
        $oUserService = (new UserServiceModel($oUser));
        if (!$oUserService->checkUserBeforeLogin()) {
            Auth::logout();
            return responseCommon()->validationMessages(null, [
                'email' => __('auth.account_banned'),
            ]);
        }
        if ($request->exists('dev')) {
            $token = $this->getToken($oUser, User::TOKEN_AUTH_DEV_NAME);
        } else {
            $token = $this->getToken($oUser);
        }
        return responseCommon()->apiDataSuccess([
            'token' => $token,
        ], __('auth.login_success'));
    }
}
