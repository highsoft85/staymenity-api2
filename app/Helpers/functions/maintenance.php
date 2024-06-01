<?php

declare(strict_types=1);

if (!function_exists('isDownForMaintenance')) {
    /**
     * @return bool
     */
    function isDownForMaintenance(): bool
    {
        return \Illuminate\Support\Facades\App::isDownForMaintenance();
    }
}
