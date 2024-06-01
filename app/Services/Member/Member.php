<?php

declare(strict_types=1);

namespace App\Services\Member;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class Member
{
    /**
     * @var User|mixed|null
     */
    public $oUser;

    /**
     * Member constructor.
     * @param mixed $oUser
     */
    public function __construct($oUser = null)
    {
        $this->oUser = $oUser ?? Auth::user();
    }

    /**
     * Возвращает экземпляр класса для текущего авторизованного юзера.
     * @return Member
     */
    public static function current()
    {
        $oUser = Auth::user();
        $self = new self($oUser);
        return $self;
    }

    /**
     * Возвращает экземпляр класса для юзера по id
     * @param int $nId
     * @return Member
     */
    public static function getById(int $nId)
    {
        $oUser = User::find($nId);
        $self = new self($oUser);
        return $self;
    }


    /**
     * Возвращает всю информацию о пользователе данного экземпляра класса
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        if (!Config::get('cmf.cache.member')) {
            $aMember = $this->getMember();
        } else {
            $aMember = Cache::remember('members:user_' . $this->oUser->id, 3600, function () {
                return $this->getMember();
            });
        }
        return collect($aMember);
    }

    /**
     * @return User|\App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return $this->oUser;
    }

    /**
     * собирает и возращает информацию о пользователе по id
     * @return array
     */
    private function getMember()
    {
        $aResult['user'] = $this->oUser->toArray();
        $aResult['model'] = $this->oUser;
        $aResult['user']['role'] = $this->oUser->roles()->first()->toArray();
        $aResult['user']['roles'] = $this->oUser->roles()->get()->toArray();
        $aResult['user']['image'] = $this->oUser->image_square;
        return $aResult;
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->oUser->hasRole(User::ROLE_ADMIN) || $this->isDeveloper();
    }

    /**
     * @return bool
     */
    public function isDeveloper()
    {
        return true;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role)
    {
        return $this->oUser->hasRole($role);
    }

    /**
     * @param array $array
     * @return bool
     */
    public function hasAnyRole(array $array)
    {
        return $this->oUser->hasAnyRole($array);
    }
}
