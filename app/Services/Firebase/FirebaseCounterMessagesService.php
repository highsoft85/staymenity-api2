<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use App\Models\Chat;
use App\Models\User;

class FirebaseCounterMessagesService extends FirebaseCounterService
{
    /**
     * @var Chat
     */
    private $oChat;

    /**
     * @param Chat $oChat
     * @return $this
     */
    public function setChat(Chat $oChat)
    {
        $this->oChat = $oChat;

        return $this;
    }

    /**
     * @param int $id
     * @return string
     */
    protected function channel(int $id): string
    {
        return $this->channelCounter() . '/users/' . $id . '/messages/' . $this->oChat->id;
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
