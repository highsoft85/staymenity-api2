<?php

declare(strict_types=1);

namespace App\Cmf\Project\Role;

trait RoleSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => RoleController::NAME,
        'title' => RoleController::TITLE,
        'description' => null,
        'icon' => RoleController::ICON,
    ];
}
