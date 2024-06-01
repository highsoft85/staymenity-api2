<?php

declare(strict_types=1);

namespace App\Services\Socialite;

use App\Models\UserSocialAccount;
use App\Models\User;
use App\Services\Image\ImageType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;

class FacebookAccountService extends BaseSocialiteService
{
    /**
     *
     */
    const NAME = 'facebook';

    /**
     * @param ProviderUser $providerUser
     * @param User|null $oUser
     * @return User|mixed|null
     * @throws \Exception
     */
    public function createOrGetUser(ProviderUser $providerUser, ?User $oUser = null)
    {
        if (!is_null($oUser)) {
            /** @var UserSocialAccount|null $account */
            $account = $this->createSocialAccountByUser($oUser, self::NAME, $providerUser->getId());
        } else {
            /** @var UserSocialAccount|null $account */
            $account = UserSocialAccount::whereProvider(self::NAME)
                ->whereProviderUserId($providerUser->getId())
                ->first();
        }
        if (!is_null($account) && !is_null($account->user)) {
            return $account->user;
        } else {
            $result = transaction()->commitAction(function () use ($providerUser) {
                return $this->createUser(self::NAME, $this->getSaveData($providerUser));
            });
            if (!$result->isSuccess()) {
                info($result->getErrorMessage());
                return null;
            }
            // вернется юзер
            return $result->getData();
        }
    }

    /**
     * @param ProviderUser $providerUser
     * @return array
     */
    private function getSaveData(ProviderUser $providerUser)
    {
        return [
            'id' => $providerUser->getId(),
            'email' => $providerUser->getEmail(),
            'name' => $providerUser->getName(),
            'avatar' => $providerUser->getAvatar(),
            'role' => request()->get('role') ?? User::ROLE_HOST,
        ];
    }
}
