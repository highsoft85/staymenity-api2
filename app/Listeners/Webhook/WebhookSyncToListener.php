<?php

declare(strict_types=1);

namespace App\Listeners\Webhook;

use App\Events\Auth\RegisteredEvent;
use App\Events\Reservation\ReservationSuccessEvent;
use App\Events\Reservation\ReservationSyncToEvent;
use App\Events\Webhook\WebhookSyncToEvent;
use App\Jobs\Mail\Auth\SendMailAuthRegisteredJob;
use App\Jobs\Mail\Auth\SendMailAuthVerifyJob;
use App\Jobs\Mail\Reservation\SendMailReservationSuccessJob;
use App\Jobs\QueueCommon;
use App\Jobs\Reservation\ReservationSyncToJob;
use App\Jobs\TestJob;
use App\Jobs\Webhook\WebhookSyncToJob;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;

class WebhookSyncToListener
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
     * @param WebhookSyncToEvent $event
     * @return void
     */
    public function handle(WebhookSyncToEvent $event)
    {
        $oUser = $event->oUser;
        $status = $event->status;

        if (!config('hostfully.enabled')) {
            return;
        }
        if (QueueCommon::commandSyncIsEnabled()) {
            Bus::batch([
                [new WebhookSyncToJob($oUser, $status),],
            ])->name('Webhook Sync ' . $oUser->id)->onQueue(QueueCommon::QUEUE_NAME_SYNC)->dispatch();
        } else {
            WebhookSyncToJob::dispatchNow($oUser, $status);
        }
    }
}
