<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Payment;
use App\Models\Payout;
use App\Models\Review;
use App\Models\Type;
use League\Fractal\TransformerAbstract;

class PayoutTransformer extends TransformerAbstract
{
    /**
     * @param Payout $oItem
     * @return array
     */
    public function transformForHost(Payout $oItem)
    {
        return [
            'id' => $oItem->id,
            'title' => $this->titleForHost($oItem),
            'description' => $this->descriptionForHost($oItem),
            'amount' => $oItem->amount,
            'status' => $this->status($oItem),
            'created_at' => $oItem->created_at->toIso8601String(),
        ];
    }
    /**
     * @param Payout $oItem
     * @return string
     */
    private function titleForHost(Payout $oItem)
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
        // Klara Jefferson, Aug 24, 9 am - 1 pm
        return $oUser->fullName . ', ' . $dateTime . ', ' . $oReservation->reservationTime;
    }

    /**
     * @param Payout $oItem
     * @return string
     */
    private function descriptionForHost(Payout $oItem)
    {
        $oReservation = $oItem->reservation;
        if (is_null($oReservation)) {
            return '-';
        }
        $oListing = $oReservation->listing;
        if (is_null($oListing)) {
            return '-';
        }
        return $oListing->title ?? '-';
    }

    /**
     * @param Payout $oItem
     * @return array
     */
    private function status(Payout $oItem)
    {
        return [
            'name' => $oItem->statusIcons()[$oItem->status]['name'],
            'title' => $oItem->statusText,
        ];
    }
}
