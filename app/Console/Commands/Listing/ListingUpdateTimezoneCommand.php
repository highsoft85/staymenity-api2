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
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ListingUpdateTimezoneCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'listing:update-timezone';

    /**
     * The name and signature of the console command.
     *
     * php artisan listing:update-timezone
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
    protected $description = 'Обновление таймзоны листинга';

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

        /** @var Listing[] $oListings */
        $oListings = Listing::whereHas('location')
            ->get();

        $bar = $this->bar(count($oListings));
        foreach ($oListings as $oListing) {
            (new ListingServiceModel($oListing))->updateTimezone();
            $bar->advance();
        }
        $bar->finish();

        /** @var Reservation[] $oReservations */
        $oReservations = Reservation::all();

        $bar = $this->bar(count($oReservations));
        foreach ($oReservations as $oReservation) {
            (new ReservationServiceModel($oReservation))->updateTimezone();
            (new ReservationServiceModel($oReservation))->updateServerDatesByTimezone();
            $bar->advance();
        }
        $bar->finish();

        $this->finish();
    }
}
