<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CmfAuthenticate
{
    /**
     * Handle an incoming request. Only users with role 'admin' could pass.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect(routeCmf('auth.login', ['backTo' => url()->current()]));
        }
        /** @var User $oUser */
        $oUser = Auth::user();
        if (!$oUser->hasRole([User::ROLE_ADMIN, User::ROLE_MANAGER])) {
            Auth::logout();
            return redirect(routeCmf('auth.login', ['backTo' => url()->current()]));
        }
        return $next($request);
    }
}
