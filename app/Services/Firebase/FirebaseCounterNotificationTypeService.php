<?php

declare(strict_types=1);

namespace App\Services\Firebase;

class FirebaseCounterNotificationTypeService extends FirebaseCounterService
{
    /**
     * @param int $id
     * @return string
     */
    protected function channel(int $id): string
    {
        return $this->channelCounter() . '/users/' . $id . '/last_notification_type';
    }

    /**
     * @return string
     */
    protected function channelCounter(): string
    {
        return 'data/' . config('app.env') . '/counter';
    }

    /**
     * @param int $id
     * @return string
     */
    protected function channelUserCounter(int $id): string
    {
        return $this->channelCounter() . '/users/' . $id;
    }
}
