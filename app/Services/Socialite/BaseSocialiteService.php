<?php

declare(strict_types=1);

namespace App\Services\Socialite;

use App\Models\User;
use App\Models\UserSocialAccount;
use App\Services\Image\ImageType;
use App\Services\Model\UserServiceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

abstract class BaseSocialiteService
{
    /**
     * @var null|string
     */
    public $errorMessage = null;

    /**
     * @param string $url
     * @param User $oUser
     * @throws \Exception
     */
    protected function saveImage(string $url, User $oUser)
    {
        $destinationPath = storage_path('app/public/images/user/tmp/');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath);
        }
        $imageName = Str::random(10) . '.' . 'jpg';
        $pathFile = $destinationPath . $imageName;

        File::put($pathFile, file_get_contents($url));
        imageUpload($pathFile, $oUser, ImageType::MODEL);
    }


    /**
     * @param string $provider
     * @param array $data
     * @return User|null
     * @throws \Exception
     */
    protected function createUser(string $provider, array $data)
    {
        $account = new UserSocialAccount([
            'provider_user_id' => $data['id'],
            'provider' => $provider,
        ]);
        $fakeLogin = $provider . '-' . $data['id'];
        /** @var User|null $user */
        $user = User::where('login', $fakeLogin)->first();
        if (is_null($user)) {
            if (isset($data['email']) && !is_null($data['email'])) {
                $oUserByEmail = User::where('email', $data['email'])->first();
                if (!is_null($oUserByEmail)) {
                    $this->errorMessage = 'This email: ' . $data['email'] . ' is associated with another Staymenity account';
                    slackInfo($this->errorMessage);
                    throw new \Exception($this->errorMessage, 422);
                }
            }
            $user = User::create([
                'login' => $fakeLogin,
                'email' => $data['email'] ?? null,
                'first_name' => $data['name'],
                'password' => Hash::make(Str::random(10)),
                'register_by' => User::REGISTER_BY_SOCIAL,
                'is_has_password' => 0,
            ]);
            if (!is_null($data['avatar'])) {
                $this->saveImage($data['avatar'], $user);
            }
            $this->afterCreateUser($user, $data);
            (new UserServiceModel($user))->saveUserTimezoneByIp();
        }
        $account->user()->associate($user);
        $account->save();
        return $user;
    }

    /**
     * @param User $oUser
     * @param array $data
     */
    protected function afterCreateUser(User $oUser, array $data)
    {
        $oService = (new UserServiceModel($oUser));
        if (isset($data['role'])) {
            $role = $data['role'];
            if (in_array($role, [User::ROLE_HOST, User::ROLE_GUEST])) {
                $oService->setCurrentRole($role);
            }
        }
        $oService->afterCreate();
    }

    /**
     * @param User $oUser
     * @param string $provider
     * @param string $id
     * @return UserSocialAccount|\Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    protected function createSocialAccountByUser(User $oUser, string $provider, string $id)
    {
        /** @var UserSocialAccount|null $oSocialAccount */
        $oSocialAccount = $oUser->socialAccounts()
            ->where('provider', $provider)
            ->first();
        if (!is_null($oSocialAccount)) {
            return $oSocialAccount;
        }
        // проверка если пользователь авторизовался через соц сеть, а потом
        // пытается её приконнектить
        $oSocialAccount = UserSocialAccount::where('provider', $provider)
            ->whereProviderUserId($id)
            ->first();
        if (!is_null($oSocialAccount)) {
            $this->errorMessage = 'This ' . $provider . ' account is associated with another Staymenity account';
            slackInfo($this->errorMessage);
            throw new \Exception($this->errorMessage, 422);
        }
        $oSocialAccount = $oUser->socialAccounts()->create([
            'provider_user_id' => $id,
            'provider' => $provider,
        ]);
        return $oSocialAccount;
    }

    /**
     * @param User $oUser
     */
    public function setRoleAfterAuth(User $oUser)
    {
        if (request()->exists('role')) {
            (new UserServiceModel($oUser))->setCurrentRole(request()->get('role'));
        }
    }
}
