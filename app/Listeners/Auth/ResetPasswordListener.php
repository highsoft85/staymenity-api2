<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\ResetPasswordEvent;
use App\Jobs\Mail\Auth\SendMailAuthResetPasswordJob;
use App\Jobs\QueueCommon;
use App\Models\User;

class ResetPasswordListener
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
     * @param ResetPasswordEvent $event
     * @return void
     */
    public function handle(ResetPasswordEvent $event)
    {
        /** @var User $oUser */
        $oUser = $event->user;
        $token = $event->token;

        if (QueueCommon::commandMailIsEnabled()) {
            SendMailAuthResetPasswordJob::dispatch($oUser, $token)->onQueue(QueueCommon::QUEUE_NAME_MAIL);
        } else {
            SendMailAuthResetPasswordJob::dispatchNow($oUser, $token);
        }
        //SendMailAuthResetPasswordJob::dispatch($oUser, $token)->onQueue(QueueCommon::QUEUE_NAME_MAIL);
    }
}
