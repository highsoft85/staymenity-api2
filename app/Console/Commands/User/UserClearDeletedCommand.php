<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Logger\Logger;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserClearDeletedCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'user:clear-deleted';

    /**
     * The name and signature of the console command.
     *
     * php artisan user:clear-deleted
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
        $oUsers = User::withTrashed()
            ->where('deleted_at', '<', now()->subDay())
            ->get();

        $array = $oUsers->pluck('id')->toArray();
        $this->log(json_encode($array));
        if (!empty($array)) {
            slackInfo($array, __CLASS__);
        }

        $bar = $this->bar(count($oUsers));
        foreach ($oUsers as $oUser) {
            if ($oUser->reservations()->count() !== 0) {
                continue;
            }
            $oUser->forceDelete();
            $bar->advance();
        }
        $bar->finish();
        $this->finish();
    }
}
