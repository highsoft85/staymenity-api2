<?php

declare(strict_types=1);

namespace App\Jobs\Mail\Auth;

use App\Mail\Auth\RegisteredMail;
use App\Models\User;
use App\Services\Notification\Slack\SlackDebugNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMailAuthRegisteredJob implements ShouldQueue
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
     * SendEmailJob constructor.
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
        $message = 'Registered Mail: ' . $this->oUser->email . "\n Token: " . $this->oUser->emailToken;
        //Mail::to($this->oUser->email)->send((new RegisteredMail($this->oUser)));
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
            'mail.' . RegisteredMail::NAME,
            'user.' . $this->oUser->id,
        ];
    }
}
