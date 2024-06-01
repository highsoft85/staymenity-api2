<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth as Auth;
use App\Services\Member\Member;
use App\Services\Member\MemberRegistry;

class MemberInit
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
            $oMemberData = Member::current()->get();
            MemberRegistry::getInstance()->setMember($oMemberData->toArray());
        }
        return $next($request);
    }
}
