<?php

declare(strict_types=1);

use App\Services\Logger\Logger;

if (!function_exists('loggerNexmo')) {
    /**
     * @param mixed $data
     * @param string|null $title
     */
    function loggerNexmo($data, ?string $title = null)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }
        if (!is_null($title)) {
            $data = $title . '::' . $data;
        }
        (new Logger())->setName('nexmo')->log()->info($data);
    }
}
if (!function_exists('loggerHostfully')) {
    /**
     * @param mixed $data
     * @param string|null $title
     */
    function loggerHostfully($data, ?string $title = null)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }
        if (!is_null($title)) {
            $data = $title . '::' . $data;
        }
        $name = 'hostfully';
        if (envIsTesting()) {
            $name = 'testing/' . $name;
        }
        (new Logger())->setName($name)->log()->info($data);
    }
}
if (!function_exists('loggerUserIdentities')) {
    /**
     * @param mixed $data
     * @param string|null $title
     */
    function loggerUserIdentities($data, ?string $title = null)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }
        if (!is_null($title)) {
            $data = $title . '::' . $data;
        }
        (new Logger())->setName('users/identities')->log()->info($data);
    }
}

if (!function_exists('loggerServiceSyncReservationByService')) {
    /**
     * @param string $service
     * @return Logger
     */
    function loggerServiceSyncReservationByService(string $service)
    {
        $name = 'sync';
        if (envIsTesting()) {
            $name = 'testing/' . $name;
        }
        return (new Logger())->setName($name . '/' . $service . '/reservations')->log();
    }
}
