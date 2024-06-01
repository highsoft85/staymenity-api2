<?php

declare(strict_types=1);

namespace App\Models\Traits;

/**
 * Trait UserRolesTrait
 * @package App\Models\Traits
 *
 * @property string $roleName
 * @property string $roleIcon
 * @property mixed $roles
 *
 */
trait UserRolesTrait
{
    /**
     * @var array
     */
    protected $rolesIcons = [
        self::ROLE_ADMIN => [
            'class' => 'fa fa-adn',
            'color' => 'text-default',
        ],
        self::ROLE_MANAGER => [
            'class' => 'fa fa-user',
            'color' => 'text-default',
        ],
        self::ROLE_HOST => [
            'class' => 'fa fa-tripadvisor',
            'color' => 'text-primary',
        ],
        self::ROLE_GUEST => [
            'class' => 'fa fa-gitlab',
            'color' => 'text-warning',
        ],
        self::ROLE_OWNER => [
            'class' => 'fa fa-gitlab',
            'color' => 'text-warning',
        ],
    ];

    /**
     * @return array
     */
    public static function rolesList()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_MANAGER,
            self::ROLE_HOST,
            self::ROLE_GUEST,
            self::ROLE_OWNER,
        ];
    }

    /**
     * @return array
     */
    public function getRoleIconsAttribute()
    {
        $roles = $this->roles;
        $data = [];
        foreach ($roles as $key => $role) {
            $roleData = $this->rolesIcons[$role->name];
            $roleData['title'] = $role->title;
            $roleData['name'] = $role->name;
            $data[] = $roleData;
        }
        return $data;
    }


    /**
     * @return string
     */
    public function getRoleNameAttribute(): string
    {
        $roles = $this->roles;
        $default = 'Неизвестно';
        return !is_null($roles->first()) ? $roles->first()->toArray()['title'] : $default;
    }
}
