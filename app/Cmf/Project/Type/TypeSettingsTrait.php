<?php

declare(strict_types=1);

namespace App\Cmf\Project\Type;

trait TypeSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => TypeController::NAME,
        'title' => TypeController::TITLE,
        'description' => null,
        'icon' => TypeController::ICON,
    ];
}
