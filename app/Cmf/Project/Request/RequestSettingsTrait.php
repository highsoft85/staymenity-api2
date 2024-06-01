<?php

declare(strict_types=1);

namespace App\Cmf\Project\Request;

trait RequestSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => RequestController::NAME,
        'title' => RequestController::TITLE,
        'description' => null,
        'icon' => RequestController::ICON,
    ];
}
