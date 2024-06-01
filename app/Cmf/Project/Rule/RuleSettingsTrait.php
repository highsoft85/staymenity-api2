<?php

declare(strict_types=1);

namespace App\Cmf\Project\Rule;

trait RuleSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => RuleController::NAME,
        'title' => RuleController::TITLE,
        'description' => null,
        'icon' => RuleController::ICON,
    ];
}
