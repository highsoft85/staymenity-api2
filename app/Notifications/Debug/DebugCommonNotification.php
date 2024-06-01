<?php

declare(strict_types=1);

namespace App\Notifications\Debug;

use Illuminate\Notifications\Notification;

class DebugCommonNotification extends Notification
{
    /**
     * @var string|null
     */
    protected $host = null;

    /**
     * DebugCommonNotification constructor.
     */
    public function __construct()
    {
        $this->host = parse_url(config('app.url'))['host'] . ':' . config('app.env');
    }
}
