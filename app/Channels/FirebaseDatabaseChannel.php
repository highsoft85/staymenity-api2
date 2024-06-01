<?php

declare(strict_types=1);

namespace App\Channels;

use App\Models\User;
use App\Services\Notification\Firebase\FirebaseActionNotification;
use Illuminate\Notifications\Notification;

/**
 * Class FirebaseDatabaseChannel
 * @package App\Channels
 *
 * @deprecated
 */
class FirebaseDatabaseChannel
{
    /**
     * @param mixed|User $notifiable
     * @param Notification $notification
     * @throws \Kreait\Firebase\Exception\DatabaseException
     */
    public function send($notifiable, Notification $notification)
    {
        $oItem = $notification->toFirebaseDatabase($notifiable);
        (new FirebaseActionNotification())
            ->setUser($notifiable)
            ->notifications()
            ->send($oItem);
    }
}
