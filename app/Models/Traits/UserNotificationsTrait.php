<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Notifications\Notification;

/**
 * Trait UserRolesTrait
 * @package App\Models\Traits
 *
 * @property string $roleName
 * @property string $roleIcon
 * @property mixed $roles
 *
 */
trait UserNotificationsTrait
{

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param Notification $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return '';
    }
}
