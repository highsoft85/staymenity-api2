<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Contract\Auth\Provider;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Guard;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Contracts\Auth\Factory;

class SanctumDingoAuthProvider implements Provider
{
    /**
     * Заглушка для генерации документации с авторизацией
     * Когда в .env стоит API_AUTH_SANCTUM_ENABLED=true, то используется
     * middleware для авторизации auth:sanctum
     * иначе
     * middleware = api.auth, значит в этот метод пропустит и не заблокирует запрос
     *
     * @param Request $request
     * @param Route $route
     * @return mixed|void
     */
    public function authenticate(Request $request, Route $route)
    {
        //
    }
}
