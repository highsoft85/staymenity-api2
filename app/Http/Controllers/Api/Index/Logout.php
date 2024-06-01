<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Index;

use App\Http\Transformers\Api\FaqTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Logout
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return responseCommon()->apiSuccess();
    }
}
