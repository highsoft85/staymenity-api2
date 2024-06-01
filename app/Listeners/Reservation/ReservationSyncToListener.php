<?php

declare(strict_types=1);

namespace App\Listeners\Reservation;

use App\Events\Auth\RegisteredEvent;
use App\Events\Reservation\ReservationSuccessEvent;
use App\Events\Reservation\ReservationSyncToEvent;
use App\Jobs\Mail\Auth\SendMailAuthRegisteredJob;
use App\Jobs\Mail\Auth\SendMailAuthVerifyJob;
use App\Jobs\Mail\Reservation\SendMailReservationSuccessJob;
use App\Jobs\QueueCommon;
use App\Jobs\Reservation\ReservationSyncToJob;
use App\Jobs\TestJob;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;

class ReservationSyncToListener
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
     * @param ReservationSyncToEvent $event
     * @return void
     */
    public function handle(ReservationSyncToEvent $event)
    {
        $oReservation = $event->oReservation;

        if (!config('hostfully.enabled')) {
            return;
        }
        if (QueueCommon::commandSyncIsEnabled()) {
            Bus::batch([
                [new ReservationSyncToJob($oReservation)],
            ])->name('Reservation Sync ' . $oReservation->id)->onQueue(QueueCommon::QUEUE_NAME_SYNC)->dispatch();
        } else {
            ReservationSyncToJob::dispatchNow($oReservation);
        }
    }
}
