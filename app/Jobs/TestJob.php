<?php

declare(strict_types=1);

namespace App\Jobs;

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

class TestJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     *
     */
    public function __construct()
    {
        $this->onQueue(QueueCommon::QUEUE_NAME_SYNC);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }
        slackInfo([], 'Test');
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            'test',
        ];
    }
}
