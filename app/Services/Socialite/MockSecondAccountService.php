<?php

declare(strict_types=1);

namespace App\Services\Socialite;

use App\Models\UserSocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;

class MockSecondAccountService extends BaseSocialiteService
{
    /**
     *
     */
    const NAME = 'mock-second';

    /**
     * @param array $data
     * @param User|null $oUser
     * @return User|mixed|null
     * @throws \Exception
     */
    public function createOrGetUser(array $data, ?User $oUser = null)
    {
        if (!is_null($oUser)) {
            /** @var UserSocialAccount|null $account */
            $account = $this->createSocialAccountByUser($oUser, self::NAME, $data['id']);
        } else {
            /** @var UserSocialAccount|null $account */
            $account = UserSocialAccount::whereProvider(self::NAME)
                ->whereProviderUserId($data['id'])
                ->first();
        }
        if (!is_null($account)) {
            return $account->user;
        } else {
            $result = transaction()->commitAction(function () use ($data) {
                return $this->createUser(self::NAME, $this->getSaveData($data));
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
