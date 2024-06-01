<?php

declare(strict_types=1);

use App\Services\Transaction\Transaction;
use Illuminate\Support\Str;
use Carbon\Carbon;

if (!function_exists('reservationCode')) {
    /**
     * @return string
     */
    function reservationCode(): string
    {
        return Str::upper(Str::random(\App\Services\Model\UserReservationServiceModel::CODE_LENGTH));
    }
}

if (!function_exists('reservationFinishAt')) {
    /**
     * @param Carbon $finish_at
     * @return Carbon
     */
    function reservationFinishAt(Carbon $finish_at): Carbon
    {
        if ($finish_at->isStartOfDay()) {
            return $finish_at->copy()->subSecond();
        }
        return $finish_at;
    }
}
