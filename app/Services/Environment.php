<?php

declare(strict_types=1);

namespace App\Services;

class Environment
{
    /**
     *
     */
    const PRODUCTION = 'production';

    /**
     *
     */
    const DEVELOPMENT = 'development';

    /**
     *
     */
    const LOCAL = 'local';

    /**
     *
     */
    const TESTING = 'testing';

    /**
     *
     */
    const DOCUMENTATION = 'documentation';

    /**
     * @return bool
     */
    public static function isDocumentation()
    {
        return config('app.env') === self::DOCUMENTATION;
    }
}
