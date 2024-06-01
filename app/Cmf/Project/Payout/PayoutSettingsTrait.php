<?php

declare(strict_types=1);

namespace App\Cmf\Project\Payout;

trait PayoutSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => PayoutController::NAME,
        'title' => PayoutController::TITLE,
        'description' => null,
        'icon' => PayoutController::ICON,
    ];
}
