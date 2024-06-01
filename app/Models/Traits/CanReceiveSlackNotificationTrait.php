<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\User;

trait CanReceiveSlackNotificationTrait
{
    /**
     * @param mixed|User $notification
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function routeNotificationForSlack($notification)
    {
        return config('logging.channels.slack-debug.url');
    }
}
