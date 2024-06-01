<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;

class NotificationServiceModel
{
    /**
     * @param DatabaseNotification $oItem
     * @param User|null $oUser
     * @return string
     */
    public function getCreatedAtFormattedByUserTimezone(DatabaseNotification $oItem, ?User $oUser = null)
    {
        /** @var Carbon $createdAt */
        $createdAt = $oItem->created_at;
        if (!is_null($oUser) && !is_null($oUser->timezone)) {
            $createdAt->timezone($oUser->timezone);
        }
        if ($createdAt->isToday()) {
            return 'Today, ' . $createdAt->format('h:m A');
        }
        if ($createdAt->isYesterday()) {
            return 'Yesterday, ' . $createdAt->format('h:m A');
        }
        return $oItem->created_at->format('m-d-Y h:m A');
    }
}
