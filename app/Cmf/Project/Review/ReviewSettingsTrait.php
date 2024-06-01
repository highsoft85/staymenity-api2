<?php

declare(strict_types=1);

namespace App\Cmf\Project\Review;

trait ReviewSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => ReviewController::NAME,
        'title' => ReviewController::TITLE,
        'description' => null,
        'icon' => ReviewController::ICON,
    ];
}
