<?php

declare(strict_types=1);

namespace App\Cmf\Project\User;

trait UserSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => UserController::NAME,
        'title' => UserController::TITLE,
        'description' => null,
        'icon' => UserController::ICON,
    ];
}
