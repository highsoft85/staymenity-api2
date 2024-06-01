<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Console\Commands\Common\CommandTrait;
use App\Jobs\Mail\User\SendMailUserHaveNewMessageJob;
use App\Jobs\QueueCommon;
use App\Models\ChatMessage;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserCalendar;
use App\Notifications\User\HaveNewMessageNotification;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Logger\Logger;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserCheckCalendarCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'user:check-calendar';

    /**
     * The name and signature of the console command.
     *
     * php artisan user:check-calendar
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
    protected $description = 'Проверка календарей чтобы не было дат из хостфулли, у которых нет hostfully_reservation модели';

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
        $this->logger = (new Logger())->setName('users')->log();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->start();

        $aDates = [];

        User::whereHas('listingsActive')->orderBy('id')->chunk(100, function ($oUsers) use (&$aUsers) {
            foreach ($oUsers as $oUser) {
                /** @var User $oUser */
                /** @var Listing[] $oListings */
                $oListings = $oUser->listingsActive()->whereHas('hostfully')->get();
                foreach ($oListings as $oListing) {
                    /** @var UserCalendar[] $oDates */
                    $oDates = $oListing->calendarDates()
                        ->locked()
                        ->where('listing_id', $oListing->id)
                        ->whereNotNull('hostfully_reservation_id')
                        ->get();

                    foreach ($oDates as $oDate) {
                        if (is_null($oDate->hostfully)) {
                            $aDates[] = ['id' => $oDate->id, 'date' => $oDate->date_at->format('d-m-Y')];
                        }
                    }
                }
            }
        });
        if (!empty($aDates)) {
            $this->log(json_encode($aDates), 'HAS WRONG CALENDAR DATES');
            slackInfo($aDates, __CLASS__);
        }
        $this->finish();
    }
}
