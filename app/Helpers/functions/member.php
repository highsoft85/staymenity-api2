<?php

declare(strict_types=1);

if (!function_exists('member')) {
    /**
     * @return \App\Services\Member\Member
     */
    function member()
    {
        return new \App\Services\Member\Member();
    }
}
