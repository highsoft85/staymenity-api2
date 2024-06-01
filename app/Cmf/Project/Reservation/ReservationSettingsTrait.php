<?php

declare(strict_types=1);

namespace App\Cmf\Project\Reservation;

trait ReservationSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => ReservationController::NAME,
        'title' => ReservationController::TITLE,
        'description' => null,
        'icon' => ReservationController::ICON,
    ];
}
