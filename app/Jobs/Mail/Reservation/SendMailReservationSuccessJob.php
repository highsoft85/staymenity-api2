<?php

declare(strict_types=1);

namespace App\Jobs\Mail\Reservation;

use App\Mail\Auth\RegisteredMail;
use App\Mail\Reservation\SuccessMail;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Notification\Slack\SlackDebugNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMailReservationSuccessJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var User|object
     */
    protected $oUser;

    /**
     * @var Reservation
     */
    protected $oReservation;

    /**
     * SendEmailJob constructor.
     * @param object|User $oUser
     * @param Reservation $oReservation
     */
    public function __construct(object $oUser, Reservation $oReservation)
    {
        $this->oUser = $oUser;
        $this->oReservation = $oReservation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = 'Reservation Success Mail: ' . $this->oUser->email . "\n Reservation: " . $this->oReservation->id . "\n Date: " . $this->oReservation->paymentDescriptionDate;
        //Mail::to($this->oUser->email)->send((new SuccessMail($this->oUser, $this->oReservation)));
        (new SlackDebugNotification())->send($message);
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            'mail.' . SuccessMail::NAME,
            'user.' . $this->oUser->id,
        ];
    }
}
