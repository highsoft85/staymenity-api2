<?php

declare(strict_types=1);

namespace App\Services\Socialite;

use App\Models\UserSocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;

class MockAccountService extends BaseSocialiteService
{
    /**
     *
     */
    const NAME = 'mock';

    /**
     * @param array $data
     * @param User|null $oUser
     * @param string $name
     * @return User|mixed|null
     * @throws \Exception
     */
    public function createOrGetUser(array $data, ?User $oUser = null, string $name = self::NAME)
    {
        if (!is_null($oUser)) {
            /** @var UserSocialAccount|null $account */
            $account = $this->createSocialAccountByUser($oUser, $name, $data['id']);
        } else {
            /** @var UserSocialAccount|null $account */
            $account = UserSocialAccount::whereProvider($name)
                ->whereProviderUserId($data['id'])
                ->first();
        }
        if (!is_null($account)) {
            return $account->user;
        } else {
            $result = transaction()->commitAction(function () use ($data, $name) {
                return $this->createUser($name, $this->getSaveData($data));
            });
            if (!$result->isSuccess()) {
                return null;
            }
            // вернется юзер
            return $result->getData();
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private function getSaveData(array $data)
    {
        return [
            'id' => $data['id'],
            'email' => $data['email'],
            'name' => $data['name'],
            'avatar' => $data['avatar'],
            'role' => $data['role'] ?? User::ROLE_HOST,
        ];
    }
}
