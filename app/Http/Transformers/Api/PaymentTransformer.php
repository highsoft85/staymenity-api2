<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Payment;
use App\Models\Review;
use App\Models\Type;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract
{
    /**
     * @param Payment $oItem
     * @return array
     */
    public function transformForHost(Payment $oItem)
    {
        return [
            'id' => $oItem->id,
            'title' => $this->titleForHost($oItem),
            'description' => $this->descriptionForHost($oItem),
            //'from' => $this->from($oItem),
            //'to' => $this->to($oItem),
            //'reservation' => $this->reservation($oItem),
            'amount' => $oItem->amountWithoutService,
            'status' => $this->status($oItem),
            //'created_at' => $oItem->created_at->toDateTimeString(),
            'created_at' => $oItem->created_at->toIso8601String(),
            'created_at_formatted' => $oItem->created_at->toFormattedDateString(),
        ];
    }

    /**
     * @param Payment $oItem
     * @return array
     */
    public function transformForGuest(Payment $oItem)
    {
        return [
            'id' => $oItem->id,
            'title' => $this->titleForGuest($oItem),
            'description' => $this->descriptionForGuest($oItem),
            //'from' => $this->from($oItem),
            //'to' => $this->to($oItem),
            //'reservation' => $this->reservation($oItem),
            'amount' => $oItem->amount,
            'status' => $this->status($oItem),
            //'created_at' => $oItem->created_at->toDateTimeString(),
            'created_at' => $oItem->created_at->toIso8601String(),
            'created_at_formatted' => $oItem->created_at->toFormattedDateString(),
        ];
    }

    /**
     * @param Payment $oItem
     * @return string
     */
    private function titleForHost(Payment $oItem)
    {
        $oReservation = $oItem->reservation;
        if (is_null($oReservation)) {
            return '-';
        }
        $oUser = $oReservation->user;
        if (is_null($oUser)) {
            return '-';
        }
        $start_at = $oReservation->start_at;
        $dateTime = $start_at->shortEnglishMonth . ' ' . $start_at->day;
        return $oUser->fullName . ', ' . $dateTime . ', ' . $oReservation->reservationTime;
    }

    /**
     * @param Payment $oItem
     * @return string
     */
    private function titleForGuest(Payment $oItem)
    {
        $oReservation = $oItem->reservation;
        if (is_null($oReservation)) {
            return '-';
        }
        $oListing = $oReservation->listing;
        if (is_null($oListing)) {
            return '-';
        }
        $oUser = $oListing->user;
        if (is_null($oUser)) {
            return '-';
        }
        $start_at = $oReservation->start_at;
        $dateTime = $start_at->shortEnglishMonth . ' ' . $start_at->day;
        return $oUser->fullName . ', ' . $dateTime . ', ' . $oReservation->reservationTime;
    }

    /**
     * @param Payment $oItem
     * @return string|null
     */
    private function descriptionForHost(Payment $oItem)
    {
        $oReservation = $oItem->reservation;
        if (is_null($oReservation)) {
            return null;
        }
        $oListing = $oReservation->listing;
        if (is_null($oListing)) {
            return null;
        }
        return $oListing->title;
    }

    /**
     * @param Payment $oItem
     * @return string|null
     */
    private function descriptionForGuest(Payment $oItem)
    {
        $oReservation = $oItem->reservation;
        if (is_null($oReservation)) {
            return null;
        }
        $oListing = $oReservation->listing;
        if (is_null($oListing)) {
            return null;
        }
        return $oListing->title;
    }

    /**
     * @param Payment $oItem
     * @return array
     */
    private function from(Payment $oItem)
    {
        return (new UserTransformer())->transformMention($oItem->userFrom);
    }

    /**
     * @param Payment $oItem
     * @return array
     */
    private function to(Payment $oItem)
    {
        return (new UserTransformer())->transformMention($oItem->userTo);
    }

    /**
     * @param Payment $oItem
     * @return array
     */
    private function reservation(Payment $oItem)
    {
        return (new ReservationTransformer())->transform($oItem->reservation);
    }

    /**
     * @param Payment $oItem
     * @return array
     */
    private function status(Payment $oItem)
    {
        return [
            'name' => $oItem->statusIcons()[$oItem->status]['name'],
            'title' => $oItem->statusText,
        ];
    }
}
