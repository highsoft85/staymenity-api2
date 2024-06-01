<?php

declare(strict_types=1);

namespace App\Jobs\Webhook;

use App\Jobs\QueueCommon;
use App\Mail\Auth\RegisteredMail;
use App\Mail\Reservation\SuccessMail;
use App\Mail\User\UserHaveNewMessageMail;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Hostfully\HostfullyLeadsService;
use App\Services\Model\UserServiceModel;
use App\Services\Notification\Slack\SlackDebugNotification;
use App\Services\Sync\Hostfully\HostfullyWebhookService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class WebhookSyncToJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * @var User
     */
    protected $oUser;

    /**
     * @var bool
     */
    protected $status;

    /**
     * @param User $oUser
     * @param bool $status
     */
    public function __construct(User $oUser, bool $status)
    {
        $this->oUser = $oUser;
        $this->status = $status;

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
            'user_id' => $this->oUser->id, 'status' => $this->status,
        ], 'USER WEBHOOKS SYNC TO');

        if (!healthCheckHostfully()->isActive()) {
            slackInfo([], 'HOSTFULLY IS NOT ACTIVE');
            return;
        }

        if ($this->status) {
            (new HostfullyWebhookService())->activateIntegrations($this->oUser);
        } else {
            try {
                (new HostfullyWebhookService())->deactivateIntegrations($this->oUser);
            } catch (\Exception $e) {
                //
            }
            $this->oUser->details()->update([
                'hostfully_agency_uid' => null,
                'hostfully_status' => 0,
            ]);
            $oListings = $this->oUser->listings()->whereHas('hostfully')->get();
            foreach ($oListings as $oListing) {
                $oListing->hostfully()->delete();
            }
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            'sync..webhooks',
            'user.' . $this->oUser->id,
        ];
    }
}
