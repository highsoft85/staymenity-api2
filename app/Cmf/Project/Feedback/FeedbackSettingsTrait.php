<?php

declare(strict_types=1);

namespace App\Cmf\Project\Feedback;

trait FeedbackSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => FeedbackController::NAME,
        'title' => FeedbackController::TITLE,
        'description' => null,
        'icon' => FeedbackController::ICON,
    ];
}
