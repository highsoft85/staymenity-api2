<?php

declare(strict_types=1);

namespace App\Cmf\Project\Balance;

trait BalanceSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => BalanceController::NAME,
        'title' => BalanceController::TITLE,
        'description' => null,
        'icon' => BalanceController::ICON,
    ];
}
