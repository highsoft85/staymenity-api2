<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Device;
use App\Services\Model\UserServiceModel;

trait CanReceivePushTrait
{
    /**
     * Route notifications for the Apn channel.
     *
     * @return string|array
     */
    public function routeNotificationForApn()
    {
        $devices = $this->devices()->where('type', Device::TYPE_IOS)->pluck('token')->toArray();
        if (empty($devices)) {
            return [];
        }
        $oService = (new UserServiceModel($this));
        if (!$oService->notificationByPushEnabled()) {
            return [];
        }
        return $devices;
    }

    /**
     * Route notifications for the Apn channel.
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        $devices = $this->devices()->where('type', Device::TYPE_WEB)->pluck('token')->toArray();
        if (empty($devices)) {
            return [];
        }
        $oService = (new UserServiceModel($this));
        if (!$oService->notificationByPushEnabled()) {
            return [];
        }
        return $devices;
    }
}
