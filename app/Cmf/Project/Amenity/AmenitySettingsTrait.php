<?php

declare(strict_types=1);

namespace App\Cmf\Project\Amenity;

trait AmenitySettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => AmenityController::NAME,
        'title' => AmenityController::TITLE,
        'description' => null,
        'icon' => AmenityController::ICON,
    ];
}
