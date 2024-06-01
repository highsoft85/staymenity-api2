<?php

declare(strict_types=1);

namespace App\Cmf\Project\OptionSystemValue;

trait OptionSystemValueSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => OptionSystemValueController::NAME,
        'title' => OptionSystemValueController::TITLE,
        'description' => null,
        'icon' => OptionSystemValueController::ICON,
    ];
}
