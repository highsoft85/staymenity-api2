<?php

declare(strict_types=1);

use Carbon\Carbon;

if (!function_exists('dateBetween')) {
    /**
     * @param Carbon $start_at
     * @param Carbon $finish_at
     * @param callable $callback
     */
    function dateBetween(Carbon $start_at, Carbon $finish_at, callable $callback)
    {
        $start = $start_at->copy();
        $end = $finish_at->copy();
        while ($start->lte($end)) {
            $callback($start, $end);
        }
    }
}
