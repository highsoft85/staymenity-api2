<?php

declare(strict_types=1);

namespace App\Cmf\Project\UserIdentity;

trait UserIdentitySettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => UserIdentityController::NAME,
        'title' => UserIdentityController::TITLE,
        'description' => null,
        'icon' => UserIdentityController::ICON,
    ];
}
