<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use App\Services\Logger\Logger;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserUpdateCountersCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'user:update-counters';

    /**
     * The name and signature of the console command.
     *
     * php artisan user:update-counters
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
    protected $description = 'Очистка удаленных юзеров';

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
        $this->start();

        // юзеры, у которых дата удаления меньше чем вчера, т.е. которые не сегодня были удалены
        /** @var User[] $oUsers */
        $oUsers = User::all();

        $array = $oUsers->pluck('id')->toArray();
        $this->log(json_encode($array));
        if (!empty($array)) {
            slackInfo($array, __CLASS__);
        }

        $bar = $this->bar(count($oUsers));
        foreach ($oUsers as $oUser) {
            $count = DatabaseNotification::where('notifiable_type', User::class)
                ->where('notifiable_id', $oUser->id)
                ->where('read_at', null)
                ->count();

            if ($count !== 0) {
                (new FirebaseCounterNotificationsService())
                    ->database()
                    ->setUser($oUser)
                    ->set($count);
            }
        }
        $bar->finish();
        $this->finish();
    }
}
