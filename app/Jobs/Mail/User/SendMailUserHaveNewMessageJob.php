<?php

declare(strict_types=1);

namespace App\Jobs\Mail\User;

use App\Mail\Auth\RegisteredMail;
use App\Mail\Reservation\SuccessMail;
use App\Mail\User\UserHaveNewMessageMail;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Model\UserServiceModel;
use App\Services\Notification\Slack\SlackDebugNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMailUserHaveNewMessageJob implements ShouldQueue
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
     * SendMailUserHaveNewMessageJob constructor.
     * @param object|User $oUser
     */
    public function __construct(object $oUser)
    {
        $this->oUser = $oUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = 'User Have New Message Mail: ' . $this->oUser->email;
        $oService = (new UserServiceModel($this->oUser));
        $enabled = $oService->notificationByMailEnabled();
        if ($enabled) {
            Mail::to($this->oUser->email)->send((new UserHaveNewMessageMail($this->oUser)));
        }
        slackInfo([
            'email' => $this->oUser->email,
            'message' => $message,
            'enabled' => $enabled,
        ], 'USER_HAVE_NEW_MESSAGE');
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            'mail.' . UserHaveNewMessageMail::NAME,
            'user.' . $this->oUser->id,
        ];
    }
}
