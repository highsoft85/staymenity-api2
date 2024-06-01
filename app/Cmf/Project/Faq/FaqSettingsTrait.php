<?php

declare(strict_types=1);

namespace App\Cmf\Project\Faq;

trait FaqSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => FaqController::NAME,
        'title' => FaqController::TITLE,
        'description' => null,
        'icon' => FaqController::ICON,
    ];
}
