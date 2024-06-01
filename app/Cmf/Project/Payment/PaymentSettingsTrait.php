<?php

declare(strict_types=1);

namespace App\Cmf\Project\Payment;

trait PaymentSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => PaymentController::NAME,
        'title' => PaymentController::TITLE,
        'description' => null,
        'icon' => PaymentController::ICON,
    ];
}
