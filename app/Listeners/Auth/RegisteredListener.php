<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\RegisteredEvent;
use App\Jobs\Mail\Auth\SendMailAuthRegisteredJob;
use App\Jobs\Mail\Auth\SendMailAuthVerifyJob;
use App\Jobs\QueueCommon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RegisteredListener
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
     * @param RegisteredEvent $event
     * @return void
     */
    public function handle(RegisteredEvent $event)
    {
        /** @var User $oUser */
        $oUser = $event->user;
        if (is_null($oUser->email)) {
            return;
        }

        if (QueueCommon::commandMailIsEnabled()) {
            SendMailAuthVerifyJob::dispatch($oUser)->onQueue(QueueCommon::QUEUE_NAME_MAIL);
        } else {
            SendMailAuthVerifyJob::dispatchNow($oUser);
        }
        //SendMailAuthRegisteredJob::dispatch($oUser)->onQueue(QueueCommon::QUEUE_NAME_MAIL);
    }
}
