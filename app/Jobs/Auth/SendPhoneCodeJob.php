<?php

declare(strict_types=1);

namespace App\Jobs\Auth;

use App\Mail\Auth\RegisteredMail;
use App\Models\User;
use App\Services\Notification\Nexmo\NexmoSendNotification;
use App\Services\Notification\Slack\SlackDebugNotification;
use App\Services\Verification\VerificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPhoneCodeJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var User|object|null
     */
    protected $oUser;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * SendEmailJob constructor.
     * @param object|User|null $oUser
     * @param string|null $phone
     * @param string|null $code
     */
    public function __construct(?object $oUser = null, ?string $phone = null, ?string $code = null)
    {
        $this->oUser = $oUser;
        $this->phone = is_null($phone) ? $this->oUser->phone : $phone;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oVerificationService = (new VerificationService())->registration();
        if (is_null($this->code)) {
            $oVerificationService->generate();
            $this->code = $oVerificationService->getCode();
        }
        if (config('nexmo.replace_number.enabled')) {
            if (config('nexmo.replace_number.from') === $this->phone) {
                $this->phone = config('nexmo.replace_number.to');
            }
        }

        if (!$oVerificationService->isTesting($this->phone)) {
            $message = 'Phone verification for: ' . $this->phone . "\n Code: " . $this->code . "\n Mode: production";
            (new NexmoSendNotification())->code($this->phone, $this->code);
        } else {
            $message = 'Phone verification for: ' . $this->phone . "\n Code: " . $this->code;
        }
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
            'phone.send.code',
            'user.',
        ];
    }
}
