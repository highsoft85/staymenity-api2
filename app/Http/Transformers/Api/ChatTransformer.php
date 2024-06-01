<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Reservation;
use App\Services\Model\UserReservationServiceModel;

class ChatTransformer
{
    /**
     * @param Chat $oItem
     * @return array
     */
    public function transformForGuest(Chat $oItem)
    {
        $oLastMessage = $this->getLastMessage($oItem);
        return [
            'id' => $oItem->id,
            'image' => $this->imageForGuest($oItem),
            //'image' => $this->imageByLastMessage($oLastMessage),
            'title' => $this->titleForGuest($oItem),
            //'title' => $this->titleByLastMessage($oLastMessage),
            'last_message' => $this->lastMessage($oItem, $oLastMessage),
            'last_message_at' => $this->lastMessageAt($oItem, $oLastMessage),
            'reservation' => $this->reservation($oItem),
            'listing' => $this->listing($oItem),
        ];
    }

    /**
     * @param Chat $oItem
     * @return array
     */
    public function transformForHost(Chat $oItem)
    {
        $oLastMessage = $this->getLastMessage($oItem);
        return [
            'id' => $oItem->id,
            'image' => $this->imageForHost($oItem),
            //'image' => $this->imageByLastMessage($oLastMessage),
            'title' => $this->titleForHost($oItem),
            //'title' => $this->titleByLastMessage($oLastMessage),
            'last_message' => $this->lastMessage($oItem, $oLastMessage),
            'last_message_at' => $this->lastMessageAt($oItem, $oLastMessage),
            'reservation' => $this->reservation($oItem),
            'listing' => $this->listing($oItem),
        ];
    }

    /**
     * @param Chat $oItem
     * @return string
     */
    private function imageForHost(Chat $oItem)
    {
        $oUser = $oItem->creatorTrashed;
        return $oUser->image_square;
    }

    /**
     * @param Chat $oItem
     * @return string
     */
    private function imageForGuest(Chat $oItem)
    {
        $oListing = $oItem->listing;
        $oUser = $oListing->userTrashed;
        return $oUser->image_square;
    }

    /**
     * @param ChatMessage $oMessage
     * @return string
     */
    private function imageByLastMessage(ChatMessage $oMessage)
    {
        return $oMessage->userTrashed->image_square;
    }

    /**
     * @param Chat $oItem
     * @return string
     */
    private function titleForHost(Chat $oItem)
    {
        $oUser = $oItem->creatorTrashed;
        return $oUser->first_name;
    }

    /**
     * @param Chat $oItem
     * @return string
     */
    private function titleForGuest(Chat $oItem)
    {
        $oListing = $oItem->listingTrashed;
        $oUser = $oListing->userTrashed;
        return $oUser->first_name;
    }

    /**
     * @param ChatMessage $oMessage
     * @return string
     */
    private function titleByLastMessage(ChatMessage $oMessage)
    {
        return $oMessage->userTrashed->first_name;
    }

    /**
     * @param Chat $oItem
     * @return array|null
     */
    private function reservation(Chat $oItem)
    {
        $oListing = $oItem->listingTrashed;
        $oUser = $oItem->creatorTrashed;

        $oReservation = (new UserReservationServiceModel($oListing, $oUser))->getReservationForChat();
        if (is_null($oReservation)) {
            return null;
        }
        return (new ReservationTransformer())->transformForChat($oReservation);
    }

    /**
     * @param Chat $oItem
     * @return array
     */
    private function listing(Chat $oItem)
    {
        $oListing = $oItem->listing;
        return (new ListingTransformer())->transformForChat($oListing);
    }

    /**
     * @param Chat $oItem
     * @param ChatMessage|null $oMessage
     * @return string
     */
    private function lastMessage(Chat $oItem, ?ChatMessage $oMessage = null)
    {
        if (!is_null($oMessage)) {
            return $oMessage->text;
        }
        return $oItem->reservation->message ?? '';
    }

    /**
     * @param Chat $oItem
     * @param ChatMessage|null $oMessage
     * @return string
     */
    private function lastMessageAt(Chat $oItem, ?ChatMessage $oMessage = null)
    {
        if (!is_null($oMessage)) {
            return $oMessage->send_at->toIso8601String();
        }
        if (!is_null($oItem->last_message_at)) {
            return $oItem->last_message_at->toIso8601String();
        }
        if (!is_null($oItem->reservation)) {
            return $oItem->reservation->created_at->toIso8601String();
        }
        return $oItem->created_at->toIso8601String();
    }

    /**
     * @param Chat $oItem
     * @return ChatMessage
     */
    private function getLastMessage(Chat $oItem)
    {
        /** @var ChatMessage|null $oMessage */
        $oMessage = $oItem->messagesActiveOrdered()->first();
        return $oMessage;
    }
}
