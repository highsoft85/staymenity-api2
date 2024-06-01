<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Type;
use App\Models\User;
use App\Models\UserSocialAccount;
use League\Fractal\TransformerAbstract;

class UserSocialAccountTransformer extends TransformerAbstract
{
    /**
     * @param UserSocialAccount $oItem
     * @return array
     */
    public function transform(UserSocialAccount $oItem)
    {
        return [
            'provider' => $oItem->provider,
        ];
    }

    /**
     * @param UserSocialAccount $oItem
     * @param User $oUser
     * @return array
     */
    public function transformByUser(UserSocialAccount $oItem, User $oUser)
    {
        return [
            'provider' => $oItem->provider,
            'connected' => $oItem->provider,
        ];
    }
}
