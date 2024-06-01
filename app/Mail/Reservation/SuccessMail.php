<?php

declare(strict_types=1);

namespace App\Mail\Reservation;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     *
     */
    const NAME = 'reservation-success';

    /**
     * @var User|object
     */
    protected $oUser;

    /**
     * @var Reservation
     */
    protected $oReservation;

    /**
     * Create a new message instance.
     *
     * @param object|User $oUser
     * @param Reservation $oReservation
     */
    public function __construct($oUser, Reservation $oReservation)
    {
        $this->oUser = $oUser;
        $this->oReservation = $oReservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = 'Reservation: ' . $this->oReservation->code . ' ' . $this->oReservation->paymentDescriptionDate;
        return $this->view('emails.reservation.success')->with([
            'title' => $title,
            'oUser' => $this->oUser,
            'oReservation' => $this->oReservation,
        ])->subject($title);
    }
}
