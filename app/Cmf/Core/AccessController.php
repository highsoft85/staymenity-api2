<?php

declare(strict_types=1);

namespace App\Cmf\Core;

use App\Models\User;
use Illuminate\Support\Facades\Session;

class AccessController
{
    const TO_SANCTUM_SESSION = 'admin:action:user_id';

    /**
     * @return bool
     */
    public function hasActionUserId()
    {
        return Session::has(self::TO_SANCTUM_SESSION);
    }

    /**
     * @return User|null
     */
    public function getActionUser()
    {
        if ($this->hasActionUserId()) {
            $id = Session::get(self::TO_SANCTUM_SESSION);
            Session::forget(self::TO_SANCTUM_SESSION);
            return User::find($id);
        }
        return null;
    }

    /**
     * @param User $oUser
     */
    public function setActionUserId(User $oUser)
    {
        Session::put(self::TO_SANCTUM_SESSION, $oUser->id);
    }
}
