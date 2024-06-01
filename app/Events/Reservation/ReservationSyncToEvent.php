<?php

declare(strict_types=1);

namespace App\Events\Reservation;

use App\Listeners\Auth\RegisteredListener;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class ReservationSyncToEvent
{
    use SerializesModels;

    /**
     * @var Reservation
     */
    public Reservation $oReservation;

    /**
     * Create a new event instance.
     * @see ReservationSyncToListener
     *
     * @param Reservation $oReservation
     * @return void
     */
    public function __construct(Reservation $oReservation)
    {
        $this->oReservation = $oReservation;
    }
}
