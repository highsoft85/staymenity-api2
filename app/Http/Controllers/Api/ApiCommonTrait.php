<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\Environment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

trait ApiCommonTrait
{
    /**
     * Для документации возьмется первую пользователь
     * Для всех остальных будет браться по Bearer token
     *
     * @param Request $request
     * @return User
     */
    protected function authUser(Request $request)
    {
        if (config('app.env') === Environment::DOCUMENTATION) {
            return User::first();
        }
        if ((new \App\Cmf\Core\AccessController())->hasActionUserId()) {
            return (new \App\Cmf\Core\AccessController())->getActionUser();
        }
        return $request->user();
    }

    /**
     * @param Request $request
     * @return User|null
     */
    protected function authHost(Request $request)
    {
        $oUser = $this->authUser($request);
        if (!$oUser->hasRole(User::ROLE_HOST)) {
            return null;
        }
        return $oUser;
    }

    /**
     * @param Request $request
     * @return User|null
     */
    protected function authGuest(Request $request)
    {
        $oUser = $this->authUser($request);
        if (!$oUser->hasRole(User::ROLE_GUEST)) {
            return null;
        }
        return $oUser;
    }
}
