<?php

declare(strict_types=1);

namespace App\Channels;

use App\Models\User;
use App\Notifications\User\Identity\UserIdentityVerificationStatusNotification;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use App\Services\Firebase\FirebaseCounterNotificationTypeService;
use App\Services\Notification\Firebase\FirebaseActionNotification;
use Illuminate\Notifications\Notification;

class FirebaseNotificationsCounterChannel
{
    /**
     * @param mixed|User $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $oUser = $notification->toFirebaseNotificationsCounter($notifiable);
        (new FirebaseCounterNotificationsService())
            ->database()
            ->setUser($oUser)
            ->increment();

        if ($notification instanceof UserIdentityVerificationStatusNotification) {
            (new FirebaseCounterNotificationTypeService())
                ->database()
                ->setUser($oUser)
                ->value(UserIdentityVerificationStatusNotification::NAME);
        }
    }
}
