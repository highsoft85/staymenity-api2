<?php

declare(strict_types=1);

namespace App\Console\Commands\Listing;

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

class ListingClearDeletedCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'listing:clear-deleted';

    /**
     * The name and signature of the console command.
     *
     * php artisan listing:clear-deleted
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
    protected $description = 'Очистка удаленных листингов';

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

        // листинги, у которых дата удаления меньше чем вчера, т.е. которые не сегодня были удалены
        /** @var Listing[] $oListings */
        $oListings = Listing::withTrashed()
            ->where('deleted_at', '<', now()->subDay())
            ->get();

        $array = $oListings->pluck('id')->toArray();
        $this->log(json_encode($array));
        if (!empty($array)) {
            slackInfo($array, __CLASS__);
        }

        $bar = $this->bar(count($oListings));
        foreach ($oListings as $oListing) {
            if ($oListing->reservations()->count() !== 0) {
                continue;
            }
            $oListing->forceDelete();
            $bar->advance();
        }
        $bar->finish();
        $this->finish();
    }
}
