<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Reservation;
use App\Models\Review;
use App\Models\Type;
use App\Models\User;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\UserReservationServiceModel;
use Carbon\Carbon;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class ReservationTransformer extends TransformerAbstract
{
    /**
     * @param Reservation $oItem
     * @return array
     */
    public function transform(Reservation $oItem)
    {
        return [
            'id' => $oItem->id,
            'user' => $this->user($oItem),
            'listing' => $this->listing($oItem),
            'message' => $oItem->message,
            'guests_size' => $oItem->guests_size,
            'date' => $this->date($oItem),
            'date_at' => $oItem->start_at->copy()->startOfDay()->toIso8601String(),
            'time' => $this->time($oItem),
            'total_price' => $oItem->total_price,
            'price' => $oItem->price,
            'chat' => null,
            'chat_can_create' => false,
            'has_review' => null,
            'free_cancellation_at' => $oItem->free_cancellation_at->toIso8601String(),
            'free_cancellation_text' => $this->freeCancellationText($oItem),
            'status' => $this->status($oItem),
        ];
    }

    /**
     * @param Reservation $oItem
     * @return array
     */
    public function transformDetail(Reservation $oItem)
    {
        return [
            'id' => $oItem->id,
            'user' => $this->user($oItem),
            'listing' => $this->listing($oItem),
            'message' => $oItem->message,
            'guests_size' => $oItem->guests_size,
            'date' => $this->date($oItem),
            'date_at' => $oItem->start_at->copy()->startOfDay()->toIso8601String(),
            'date_formatted' => $this->dateFormatted($oItem),
            'time' => $this->time($oItem),
            'hours' => $this->hours($oItem),
            'total_price' => $oItem->total_price,
            'price' => $oItem->price,
            'chat' => null,
            'chat_can_create' => false,
            'has_review' => null,
            'free_cancellation_at' => $oItem->free_cancellation_at->toIso8601String(),
            'free_cancellation_text' => $this->freeCancellationText($oItem),
            'status' => $this->status($oItem),
        ];
    }

    /**
     * @param Reservation $oItem
     * @param User $oUser
     * @return array
     */
    public function transformByUser(Reservation $oItem, User $oUser)
    {
        $aItem = $this->transform($oItem);
        if ($oUser->isHost()) {
            $aItem['chat_can_create'] = $oItem->source === Reservation::SOURCE_HOSTFULLY ? false : true;
            $aItem['total_price'] = $oItem->price;
            if ($oItem->isPassed()) {
                $aItem['has_review'] = (new ReservationServiceModel($oItem))->hasReviewFromHost();
            }
        }
        if ($oUser->isGuest()) {
            $aItem['chat_can_create'] = $oItem->source === Reservation::SOURCE_HOSTFULLY ? false : true;
            $aItem['total_price'] = $oItem->total_price;
            if ($oItem->isPassed()) {
                $aItem['has_review'] = (new ReservationServiceModel($oItem))->hasReviewFromGuest();
            }
        }
        $aItem['chat'] = $this->chatForUser($oItem, $oUser);
        if ($oItem->isCancelled() || $oItem->isDeclined()) {
            $aItem['chat'] = null;
        }
        return $aItem;
    }

    /**
     * @param Reservation $oItem
     * @return array
     */
    public function transformForChat(Reservation $oItem)
    {
        return [
            'id' => $oItem->id,
            //'listing' => $this->listingForChat($oItem),
            'time' => $this->time($oItem),
            'hours' => $this->hours($oItem),
            'date_formatted' => $this->dateFormattedForChat($oItem),
            'start_at_date' => $this->startAtFormatDate($oItem),
            'start_at_time' => $this->startAtFormatTime($oItem),
            'finish_at_date' => $this->finishAtFormatDate($oItem),
            'finish_at_time' => $this->finishAtFormatTime($oItem),
            'type' => $this->type($oItem),
            'status' => $this->status($oItem),
        ];
    }

        /**
     * @param Reservation $oItem
     * @return array
     */
    private function user(Reservation $oItem)
    {
        return (new UserTransformer())->transformMention($oItem->userTrashed);
    }

    /**
     * @param Reservation $oItem
     * @return array
     */
    private function listing(Reservation $oItem)
    {
        return (new ListingTransformer())->transformCardForReservation($oItem->listingTrashed);
    }

    /**
     * @param Reservation $oItem
     * @return array
     */
    private function listingForChat(Reservation $oItem)
    {
        return (new ListingTransformer())->transformForChat($oItem->listingTrashed);
    }

    /**
     * @param Reservation $oItem
     * @return string
     */
    private function date(Reservation $oItem)
    {
        $date = $oItem->start_at->copy()->startOfDay();
        if ($date->isToday()) {
            return 'Today';
        }
        if ($date->isTomorrow()) {
            return 'Tomorrow';
        }
        return $date->format('m-d-Y');
    }

    /**
     * @param Reservation $oItem
     * @return string
     */
    private function dateFormatted(Reservation $oItem)
    {
        $date = $oItem->start_at->copy()->startOfDay();
        return $date->format('m-d-Y') . ' (' . $this->date($oItem) . ')';
    }

    /**
     * @param Reservation $oItem
     * @return string
     */
    private function dateFormattedForChat(Reservation $oItem)
    {
        $nowTimezone = Carbon::parse(now()->startOfDay()->toDateTimeString(), $oItem->timezone);
        $date = Carbon::parse($oItem->start_at->copy()->startOfDay()->toDateTimeString(), $oItem->timezone);
        if ($nowTimezone->diffInDays($date) === 0) {
            return 'Today';
        }
        if ($date > $nowTimezone && $nowTimezone->diffInDays($date) === 1) {
            return 'Tomorrow';
        }
        return $oItem->start_at->shortMonthName . ' ' . $oItem->start_at->day;
    }

    /**
     * @param Reservation $oItem
     * @return string
     */
    private function time(Reservation $oItem)
    {
        if ($oItem->isByDays()) {
            $days = $oItem->daysCount();
            if ($days === 1) {
                $text = $days . ' day';
            } else {
                $text = $days . ' days';
            }
            return $text . ', to ' . $oItem->finish_at->copy()->addSecond()->format('m-d-Y');
        }

        $start = $oItem->start_at->copy();
        $finish = $oItem->finish_at->copy();
        $from = [
            'h' => (int)$start->format('h'),
            'A' => Str::lower($start->format('A')),
        ];
        $to = [
            'h' => (int)$finish->copy()->addMinute()->format('h'),
            'A' => Str::lower($finish->copy()->addMinute()->format('A')),
        ];
        $fromText = $from['h'] . ' ' . $from['A'];
        $toText = $to['h'] . ' ' . $to['A'];
        //$from = $oItem->start_at->format('h A');
        //$to = $oItem->finish_at->format('h A');
        return $fromText . ' to ' . $toText;
    }

    /**
     * @param Reservation $oItem
     * @return array
     */
    private function status(Reservation $oItem)
    {
        return [
            'name' => $oItem->statusName,
            'title' => $oItem->statusText,
        ];
    }

    /**
     * @param Reservation $oItem
     * @return array
     */
    private function type(Reservation $oItem)
    {
        return $oItem->getType();
    }

    /**
     * @param Reservation $oItem
     * @return int
     */
    private function hours(Reservation $oItem)
    {
        $startAt = $oItem->start_at->copy();
        $finisHAt = $oItem->finish_at->copy()->addMinute();
        return $startAt->diffInHours($finisHAt);
    }

    /**
     * @param Reservation $oItem
     * @return string
     */
    private function freeCancellationText(Reservation $oItem)
    {
        if ($oItem->source === Reservation::SOURCE_HOSTFULLY) {
            return 'Reservation cannot be cancelled. Please cancel from Hostfully.';
        }
        $formatting = $oItem->free_cancellation_at->format('h A, F d');
        $timezone = config('app.timezone');
        $nowInLondonTz = Carbon::now($timezone);
        $timezoneName = $nowInLondonTz->timezoneName;
        $timezoneName = str_replace('_', ' ', $timezoneName);
        return 'Free cancellation before ' . $formatting . ' (' . $timezoneName . ')';
    }

    /**
     * @param Reservation $oItem
     * @return string
     */
    private function startAtFormatDate(Reservation $oItem)
    {
        // Sat, Feb 15
        return $oItem->start_at->shortEnglishDayOfWeek . ', ' . $oItem->start_at->shortMonthName . ' ' . $oItem->start_at->day;
    }

    /**
     * @param Reservation $oItem
     * @return string
     */
    private function finishAtFormatDate(Reservation $oItem)
    {
        // Sat, Feb 15
        return $oItem->finish_at->shortEnglishDayOfWeek . ', ' . $oItem->finish_at->shortMonthName . ' ' . $oItem->finish_at->day;
    }

    /**
     * @param Reservation $oItem
     * @return string
     */
    private function startAtFormatTime(Reservation $oItem)
    {
        // 9:00 AM
        return $oItem->start_at->format('g:i A');
    }

    /**
     * @param Reservation $oItem
     * @return string
     */
    private function finishAtFormatTime(Reservation $oItem)
    {
        // 9:00 AM
        return $oItem->finish_at->copy()->addMinute()->format('g:i A');
    }

    /**
     * @param Reservation $oItem
     * @return array|null
     *
     * @deprecated
     */
    private function chat(Reservation $oItem)
    {
        $oChat = (new UserReservationServiceModel($oItem->listingTrashed, $oItem->userTrashed))
            ->getReservationForChat();
        if (is_null($oChat)) {
            return null;
        }
        return [
            'id' => $oChat->id,
        ];
    }

    /**
     * @param Reservation $oItem
     * @param User $oUser
     * @return array|null
     */
    private function chatForUser(Reservation $oItem, User $oUser)
    {
        $oListing = $oItem->listingTrashed;
        $oChat = $oUser->chats()->where('listing_id', $oListing->id)->first();
        if (is_null($oChat)) {
            return null;
        }
        return [
            'id' => $oChat->id,
        ];
    }
}
