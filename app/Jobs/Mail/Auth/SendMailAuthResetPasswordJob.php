<?php

declare(strict_types=1);

namespace App\Jobs\Mail\Auth;

use App\Mail\Auth\ResetPasswordMail;
use App\Models\User;
use App\Services\Notification\Slack\SlackDebugNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMailAuthResetPasswordJob implements ShouldQueue
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
     * @var string
     */
    protected $token;

    /**
     * SendEmailJob constructor.
     * @param object|User $oUser
     * @param string $token
     */
    public function __construct(object $oUser, string $token)
    {
        $this->oUser = $oUser;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = 'Reset Password Mail: ' . $this->oUser->email . "\n Token: " . $this->token;
        Mail::to($this->oUser->email)->send((new ResetPasswordMail($this->oUser, $this->token)));
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
            'mail.' . ResetPasswordMail::NAME,
            'user.' . $this->oUser->id,
        ];
    }
}
