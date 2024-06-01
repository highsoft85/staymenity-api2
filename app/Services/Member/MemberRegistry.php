<?php

declare(strict_types=1);

namespace App\Services\Member;

use App\Models\User;
use Illuminate\Support\Arr;

class MemberRegistry
{
    private static $instance = null;

    private $registry = [];

    private $aMemberData = [];

    public $nAgentId = 0;
    public $canAdminView = false;
    public $canAdminEdit = false;
    public $canAdminDelete = false;
    public $canDevView = false;

    /**
     * MemberRegistry constructor.
     */
    // public function __construct()
    // {
    //     /* ... @return MemberRegistry */
    // }

    // private function __clone()
    // {
    //     /* ... @return MemberRegistry */
    // }

    // private function __wakeup()
    // {
    //     /* ... @return MemberRegistry */
    // }

    /**
     * @return MemberRegistry|null
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set($key, $object)
    {
        $this->registry[$key] = $object;
    }

    public function get($key, $default = null)
    {
        $mResult = Arr::get($this->registry, $key, $default);
        return (is_array($mResult) && !$default) ? collect($mResult) : $mResult;
    }

    public function getUser()
    {
        return Arr::get($this->registry, 'user');
    }

    public function getRole()
    {
        return $this->get('role.slug', 'quest');
    }

    public function hasRole($sRole)
    {
        return ($sRole === $this->get('role.slug'));
    }

    public function getPerson($sFieldName = '')
    {
        return (!$sFieldName)
            ? collect(Arr::get($this->registry, 'person'))
            : Arr::get($this->registry, 'person.' . $sFieldName);
    }

    public function getCompany($sFieldName = '')
    {
        return (!$sFieldName)
            ? collect(Arr::get($this->registry, 'company'))
            : Arr::get($this->registry, 'company.' . $sFieldName);
    }

    public function getMember()
    {
        return collect($this->registry);
    }

    public function setMember($aMemberData)
    {
        $this->aMemberData = $aMemberData;
        foreach ($aMemberData as $key => $value) {
            $this->set($key, $value);
        }
        $this->nAgentId = $this->get('agent.id');
    }
}
