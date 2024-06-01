<?php

declare(strict_types=1);

if (!function_exists('byEnv')) {
    /**
     * Напускать задачу после проверки по env
     *
     * @param string|array $env '*', 'production', ['production', 'local']
     * @param callable|null $function
     */
    function byEnv($env = '*', ?callable $function = null)
    {
        if (is_null($function)) {
            return;
        }
        if (checkEnv($env)) {
            $function();
        }
    }
}

if (!function_exists('byEnvLocal')) {
    /**
     * Напускать задачу после проверки по env
     *
     * @param callable|null $function
     */
    function byEnvLocal(?callable $function = null)
    {
        if (is_null($function)) {
            return;
        }
        if (checkEnv(\App\Services\Environment::LOCAL)) {
            $function();
        }
    }
}

if (!function_exists('byEnvProduction')) {
    /**
     * Напускать задачу после проверки по env
     *
     * @param callable|null $function
     */
    function byEnvProduction(?callable $function = null)
    {
        if (is_null($function)) {
            return;
        }
        if (checkEnv(\App\Services\Environment::PRODUCTION)) {
            $function();
        }
    }
}

if (!function_exists('checkEnv')) {
    /**
     * Напускать задачу после проверки по env
     *
     * @param string|array $env '*', 'production', ['production', 'local']
     * @return bool
     */
    function checkEnv($env = '*'): bool
    {
        if (is_array($env)) {
            if (in_array(config('app.env'), $env)) {
                return true;
            }
        } else {
            if ($env === '*' || config('app.env') === $env) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('envIsDocumentation')) {
    /**
     * @return bool
     */
    function envIsDocumentation(): bool
    {
        return checkEnv(\App\Services\Environment::DOCUMENTATION);
    }
}

if (!function_exists('envIsProduction')) {
    /**
     * @return bool
     */
    function envIsProduction(): bool
    {
        return checkEnv(\App\Services\Environment::PRODUCTION);
    }
}

if (!function_exists('envIsTesting')) {
    /**
     * @return bool
     */
    function envIsTesting(): bool
    {
        return checkEnv(\App\Services\Environment::TESTING);
    }
}
