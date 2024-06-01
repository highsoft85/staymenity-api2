<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth\Verify;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\Auth\Verify\VerifySuccessRequest;
use App\Models\User;
use App\Services\Environment;
use App\Services\Socialite\FacebookAccountService;
use App\Services\Socialite\GoogleAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;

class Success extends AuthController
{
    /**
     * @param VerifySuccessRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(VerifySuccessRequest $request)
    {
        $data = $request->validated();
        $email = Crypt::decrypt($data['token']);
        /** @var User|null $oUser */
        $oUser = User::where('email', $data['email'])->first();
        if (is_null($oUser) || $email !== $data['email']) {
            return responseCommon()->apiNotFound();
        }
        $oUser->update([
            'email_verified_at' => now(),
        ]);
        return responseCommon()->apiSuccess([], 'Your email has been successfully verified');
    }
}
