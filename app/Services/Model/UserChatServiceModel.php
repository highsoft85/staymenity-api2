<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\HaveNewMessageNotification;

class UserChatServiceModel
{
    /**
     * @var User
     */
    private $oUser;

    /**
     * @var Chat|null
     */
    private $oChat;

    /**
     * ChatServiceModel constructor.
     * @param User $oUser
     * @param Chat|null $oChat
     */
    public function __construct(User $oUser, ?Chat $oChat = null)
    {
        $this->oUser = $oUser;
        $this->oChat = $oChat;
    }

    /**
     * @param Reservation $oReservation
     * @param array $data
     * @return Chat
     */
    public function createByReservation(Reservation $oReservation, array $data = [])
    {
        $oListing = $oReservation->listing;
        $oHost = $oListing->user;
        $oGuest = $oReservation->user;

        $oChat = $this->getExistingChat($oListing);
        if (!is_null($oChat)) {
            $this->oChat = $oChat;
            if (!is_null($oReservation->message)) {
                $this->message($oReservation->message, $oGuest, true);
                $this->updateLastMessage();
            }
        } else {
            /** @var Chat $oChat */
            $this->oChat = Chat::create([
                'owner_id' => $oHost->id,
                'creator_id' => $oGuest->id,
                'listing_id' => $oListing->id,
            ]);
            $oHost->chats()->attach($this->oChat);
            if ($oHost->id !== $oGuest->id) {
                $oGuest->chats()->attach($this->oChat);
            }

            if (!is_null($oReservation->message)) {
                $this->message($oReservation->message, $oGuest, true);
            }
            $this->updateLastMessage();
        }
        return $this->oChat;
    }

    /**
     *
     */
    private function updateLastMessage()
    {
        $this->oChat->update([
            'last_message_at' => now(),
        ]);
    }

    /**
     * @param Listing $oListing
     * @return null|Chat
     */
    private function getExistingChat(Listing $oListing)
    {
        $oChat = Chat::where('creator_id', $this->oUser->id)
            ->where('listing_id', $oListing->id)
            ->first();
        if (!is_null($oChat)) {
            return $oChat;
        }
        $oChat = Chat::where('owner_id', $this->oUser->id)
            ->where('listing_id', $oListing->id)
            ->first();
        if (!is_null($oChat)) {
            return $oChat;
        }
        return null;
    }

    /**
     * @param Listing $oListing
     * @param array $data
     * @return Chat|null
     */
    public function createByListing(Listing $oListing, array $data = [])
    {
        $oHost = $oListing->user;
        $oGuest = $this->oUser;

        $oChat = $this->getExistingChat($oListing);

        if (!is_null($oChat)) {
            $this->oChat = $oChat;
        } else {
            /** @var Chat $oChat */
            $this->oChat = Chat::create([
                'owner_id' => $oHost->id,
                'creator_id' => $oGuest->id,
                'listing_id' => $oListing->id,
            ]);
            $oHost->chats()->attach($this->oChat);

            if ($oHost->id !== $oGuest->id) {
                $oGuest->chats()->attach($this->oChat);
            }

            $this->updateLastMessage();
        }

        return $this->oChat;
    }

    /**
     * @param string $message
     * @param User|null $oFromUser
     * @param bool $isAutoMessage
     * @return ChatMessage
     */
    public function message(string $message, ?User $oFromUser = null, bool $isAutoMessage = false)
    {
        if (is_null($oFromUser)) {
            $oFromUser = $this->oUser;
        }
        /** @var ChatMessage $oMessage */
        $oMessage = $this->oChat->messages()->create([
            'user_id' => $oFromUser->id,
            'text' => $message,
            'send_at' => now(),
        ]);
//        if (!$isAutoMessage) {
//
//        }
        $oUsers = $this->oChat->users;
        foreach ($oUsers as $oUser) {
            // отправить всем кроме того кто отправил
            if ($oFromUser->id !== $oUser->id) {
                $oUser->notify(new HaveNewMessageNotification($oFromUser, $this->oChat, $message));
            }
        }
        return $oMessage;
    }

    /**
     *
     */
    public function delete()
    {
        try {
            $this->oChat->delete();
        } catch (\Exception $e) {
            //
        }
    }
}
