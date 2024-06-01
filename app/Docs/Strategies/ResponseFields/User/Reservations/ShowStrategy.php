<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Reservations;

use App\Docs\Strategies\Fields\Reservation\ReservationFieldsTrait;
use App\Http\Transformers\Api\ReservationTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Reservation;
use App\Models\User;
use App\Docs\Strategy;
use App\Models\UserSave;

class ShowStrategy extends Strategy
{
    use ReservationFieldsTrait;

    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_reservations_show;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        $data = [
            'id' => $this->reservationId(),
            'user' => $this->reservationUser(),
            'listing' => $this->reservationListing(),
            'message' => $this->reservationMessage(),
            'guests_size' => $this->reservationGuestsSize(),
            'date' => $this->reservationDate(),
            'date_at' => $this->reservationDateAt(),
            'date_formatted' => $this->reservationDateFormatted(),
            'time' => $this->reservationTime(),
            'price' => $this->reservationPrice(),
            'hours' => $this->reservationHours(),
            'chat' => $this->reservationChat(),
            'chat_can_create' => $this->reservationChatCanCreate(),
            'has_review' => $this->reservationHasReview(),
            'total_price' => $this->reservationTotalPrice(),
            'free_cancellation_at' => $this->reservationFreeCancellationAt(),
            'free_cancellation_text' => $this->reservationFreeCancellationText(),
            'status' => $this->reservationStatus(),
        ];
        return $this->withCheckKeys($data);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        /** @var Reservation $oReservation */
        $oReservation = $this->user()->reservations()->first();
        return (new ReservationTransformer())->transformDetail($oReservation);
    }
}
