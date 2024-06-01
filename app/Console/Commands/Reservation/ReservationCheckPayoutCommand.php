<?php

declare(strict_types=1);

namespace App\Console\Commands\Reservation;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Logger\Logger;
use App\Services\Model\PayoutServiceModel;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use App\Services\Payment\Stripe\PaymentPayoutService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ReservationCheckPayoutCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'reservation:check-payout';

    /**
     * The name and signature of the console command.
     *
     * php artisan reservation:check-payout
     * php artisan user:create --testing
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
    protected $description = 'Создание payout если есть трансфер, но вывода так и не было';

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
        $this->logger = (new Logger())->setName('reservations/payout')->log();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->start();

        // брони, у которых есть трансфер, которых происходит на начало резервации, но неь вывода
        /** @var Reservation[] $oReservations */
        $oReservations = Reservation::whereNotNull('accepted_at')
            ->where('status', Reservation::STATUS_ACCEPTED)
            ->whereNull('payout_id')
            ->whereNull('payout_at')
            ->whereNotNull('transfer_id')
            ->whereNotNull('transfer_at')
            ->get();

        $array = $oReservations->pluck('id')->toArray();
        $this->log(json_encode($array));
        if (!empty($array)) {
            slackInfo($array, __CLASS__);
        }

        $bar = $this->bar(count($oReservations));
        foreach ($oReservations as $oReservation) {
            // создание вывода
            try {
                (new ReservationServiceModel($oReservation))->makePayout();
            } catch (\Exception $e) {
                $array = [
                    'id' => $oReservation->transferDescription,
                    'item' => 'payout not created',
                    'message' => $e->getMessage(),
                ];
                $this->log(json_encode($array));
                if (!empty($array)) {
                    slackInfo($array, __CLASS__);
                }
                //
            }
            $bar->advance();
        }
        $bar->finish();
        $this->finish();
    }
}
