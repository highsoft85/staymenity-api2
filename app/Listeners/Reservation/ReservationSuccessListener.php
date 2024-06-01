<?php

declare(strict_types=1);

namespace App\Listeners\Reservation;

use App\Events\Auth\RegisteredEvent;
use App\Events\Reservation\ReservationSuccessEvent;
use App\Jobs\Mail\Auth\SendMailAuthRegisteredJob;
use App\Jobs\Mail\Auth\SendMailAuthVerifyJob;
use App\Jobs\Mail\Reservation\SendMailReservationSuccessJob;
use App\Jobs\QueueCommon;
use App\Models\User;
use App\Services\Model\ReservationServiceModel;
use Illuminate\Support\Facades\Mail;

class ReservationSuccessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ReservationSuccessEvent $event
     * @return void
     */
    public function handle(ReservationSuccessEvent $event)
    {
        /** @var User $oUser */
        $oUser = $event->user;
        $oReservation = $event->reservation;
        if (is_null($oUser->email)) {
            return;
        }
        (new ReservationServiceModel($oReservation))->eventSync();

        if (QueueCommon::commandMailIsEnabled()) {
            SendMailReservationSuccessJob::dispatch($oUser, $oReservation)->onQueue(QueueCommon::QUEUE_NAME_MAIL);
        } else {
            SendMailReservationSuccessJob::dispatchNow($oUser, $oReservation);
        }
        //SendMailAuthRegisteredJob::dispatch($oUser)->onQueue(QueueCommon::QUEUE_NAME_MAIL);
    }
}
