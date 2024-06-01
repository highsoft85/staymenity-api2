<?php

declare(strict_types=1);

namespace App\Jobs\Reservation;

use App\Jobs\QueueCommon;
use App\Mail\Auth\RegisteredMail;
use App\Mail\Reservation\SuccessMail;
use App\Mail\User\UserHaveNewMessageMail;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Hostfully\HostfullyLeadsService;
use App\Services\Model\UserServiceModel;
use App\Services\Notification\Slack\SlackDebugNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class ReservationSyncToJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * @var Reservation
     */
    protected $oReservation;

    /**
     * @param Reservation $oReservation
     */
    public function __construct(Reservation $oReservation)
    {
        $this->oReservation = $oReservation;

        $this->onQueue(QueueCommon::QUEUE_NAME_SYNC);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        slackInfo([
            'reservation' => $this->oReservation->id,
        ], 'RESERVATION SYNC TO');

        if (!healthCheckHostfully()->isActive()) {
            slackInfo([], 'HOSTFULLY IS NOT ACTIVE');
            return;
        }

        (new HostfullyLeadsService($this->oReservation->listing->user->details->hostfully_agency_uid))->syncTo($this->oReservation);
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            'sync.to',
            'reservation.' . $this->oReservation->id,
        ];
    }
}
