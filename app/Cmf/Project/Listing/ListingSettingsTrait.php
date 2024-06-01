<?php

declare(strict_types=1);

namespace App\Cmf\Project\Listing;

trait ListingSettingsTrait
{
    /**
     * Visible sidebar menu
     *
     * @var array
     */
    public $menu = [
        'name' => ListingController::NAME,
        'title' => ListingController::TITLE,
        'description' => null,
        'icon' => ListingController::ICON,
    ];
}
