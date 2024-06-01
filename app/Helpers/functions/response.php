<?php

declare(strict_types=1);

use App\Services\ResponseCommon\ResponseCommonHelpers;

if (!function_exists('responseCommon')) {
    /**
     * @return ResponseCommonHelpers
     */
    function responseCommon()
    {
        return (new ResponseCommonHelpers());
    }
}
