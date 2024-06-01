<?php

declare(strict_types=1);

namespace App\Console\Commands\Dev;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Logger\Logger;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ScheduleRunCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'dev:schedule-run';

    /**
     * The name and signature of the console command.
     *
     * php artisan dev:schedule-run
     *
     * @var string
     */
    protected $signature = self::SIGNATURE . '
        {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск расписания на проде';

    /**
     * @var bool
     */
    private $log = false;

    /**
     * @var Logger|null
     */
    private $logger = null;

    /**
     * CheckQueuesCommand constructor.
     */
    public function __construct()
    {
        @parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $url = 'https://api.staymenity.com';
        $response = Http::get($url . '/api/dev/cron/run');
        if ($response->status() !== 200) {
            slackInfo($response->body());
        }
    }
}
