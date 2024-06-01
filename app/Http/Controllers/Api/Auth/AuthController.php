<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

abstract class AuthController extends Controller
{
    /**
     * @param User|Authenticatable $oUser
     * @param string $name
     * @return string
     */
    protected function getToken($oUser, string $name = User::TOKEN_AUTH_NAME): string
    {
        $oToken = $oUser->tokens()->where('name', $name)->first();
        if (is_null($oToken)) {
            $token = $oUser->createToken($name);
            $token = $token->plainTextToken;
        } else {
            $oUser->tokens()->where('name', $name)->delete();
            $token = $oUser->createToken($name);
            $token = $token->plainTextToken;
        }
        $oUser->update([
            'last_login_at' => now(),
        ]);
        return $token;
    }

    /**
     * @param array $data
     * @return string|null
     */
    protected function getPhoneByDataSendVerify(array $data)
    {
        $phone = null;
        if (isset($data['user_id'])) {
            /** @var User $oUser */
            $oUser = User::find($data['user_id']);
            $phone = $oUser->phone;
        }
        if (isset($data['phone'])) {
            $phone = preg_replace('/[^0-9]/', '', $data['phone']);
        }
        return $phone;
    }

    /**
     * @param string|null $phone
     * @return bool
     */
    protected function checkPhone(?string $phone)
    {
        return !is_null($phone) && strlen($phone) === 11;
    }
}
