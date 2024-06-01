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

class ListingUpdateRatingCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'listing:update-rating';

    /**
     * The name and signature of the console command.
     *
     * php artisan listing:update-rating
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
    protected $description = 'Обновление рейтинга';

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
        $oListings = Listing::whereNotNull('published_at')
            ->get();

        $bar = $this->bar(count($oListings));
        foreach ($oListings as $oListing) {
            (new ListingServiceModel($oListing))->updateRating();
            $bar->advance();
        }
        $bar->finish();
        $this->finish();
    }
}
