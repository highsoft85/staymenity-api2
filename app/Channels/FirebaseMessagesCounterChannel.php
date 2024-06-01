<?php

declare(strict_types=1);

namespace App\Channels;

use App\Models\User;
use App\Services\Firebase\FirebaseCounterMessagesService;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use App\Services\Notification\Firebase\FirebaseActionNotification;
use Illuminate\Notifications\Notification;

class FirebaseMessagesCounterChannel
{
    /**
     * @param mixed|User $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $oUser = $notification->toFirebaseMessagesCounter($notifiable);
        (new FirebaseCounterMessagesService())
            ->database()
            ->setUser($oUser)
            ->setChat($notification->getChat())
            ->increment();
    }
}
