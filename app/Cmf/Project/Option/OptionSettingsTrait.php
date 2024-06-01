<?php

declare(strict_types=1);

namespace App\Cmf\Project\Option;

trait OptionSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => OptionController::NAME,
        'title' => OptionController::TITLE,
        'description' => null,
        'icon' => OptionController::ICON,
    ];
}
