<?php

declare(strict_types=1);

namespace App\Events\Reservation;

use App\Listeners\Auth\RegisteredListener;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class ReservationSuccessEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Authenticatable|User
     */
    public $user;

    /**
     * @var Reservation
     */
    public $reservation;

    /**
     * Create a new event instance.
     * @see RegisteredListener
     *
     * @param Authenticatable|User $user
     * @param Reservation $reservation
     * @return void
     */
    public function __construct($user, Reservation $reservation)
    {
        $this->user = $user;
        $this->reservation = $reservation;
    }
}
