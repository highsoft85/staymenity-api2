<?php

declare(strict_types=1);

use App\Services\Transaction\Transaction;

if (!function_exists('transaction')) {
    /**
     * @return Transaction
     */
    function transaction(): Transaction
    {
        return (new Transaction());
    }
}
