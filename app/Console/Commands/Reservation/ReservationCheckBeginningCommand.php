<?php

declare(strict_types=1);

namespace App\Console\Commands\Reservation;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\LeaveReviewNotification;
use App\Notifications\User\Reservation\ReservationTransferNotification;
use App\Services\Logger\Logger;
use App\Services\Model\PayoutServiceModel;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use App\Services\Payment\Stripe\PaymentPayoutService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ReservationCheckBeginningCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'reservation:check-beginning';

    /**
     * The name and signature of the console command.
     *
     * php artisan reservation:check-beginning
     * php artisan user:create --testing
     *
     * @var string
     */
    protected $signature = self::SIGNATURE . '
        {--transfer=true : по умолчанию включены трансферы}
        {--payout=false : по умолчанию выключены выводы}
        {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Поиск начавшихся бронирований';

    /**
     * @var bool
     */
    private $log = false;

    /**
     * @var bool
     */
    private $transfer = true;

    /**
     * @var bool
     */
    private $payout = false;

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
        $this->logger = (new Logger())->setName('reservations/beginning')->log();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->start();

        $now = now();

        // начало часа - 1 минута, т.е. 09:59
        // конец часа - 1 минута, т.е. 10:59
        $hourBetween = [
            now()->startOfHour()->subMinute(),
            now()->endOfHour(),
        ];

        /** @var Reservation[] $oReservations */
        $oReservations = Reservation::whereNotNull('accepted_at')
            ->where('status', Reservation::STATUS_ACCEPTED)
            ->whereBetween('server_start_at', $hourBetween)
            ->whereNull('beginning_at')
            ->whereNull('passed_at')
            ->get();

        $array = $oReservations->pluck('id')->toArray();
        $this->log(json_encode($array));
        if (!empty($array)) {
            slackInfo($array, __CLASS__);
        }

        $bar = $this->bar(count($oReservations));
        foreach ($oReservations as $oReservation) {
            // началось
            $oReservation->update([
                'beginning_at' => $now,
            ]);

            // создание трансфера
            if ($this->transfer && $oReservation->fromApp()) {
                try {
                    (new ReservationServiceModel($oReservation))->makeTransfer();
                    $oHost = $oReservation->listing->user;
                    $oHost->notify(new ReservationTransferNotification($oReservation));
                } catch (\Exception $e) {
                    $array = [
                        'id' => $oReservation->transferDescription,
                        'item' => 'transfer not created',
                        'message' => $e->getMessage(),
                    ];
                    $this->log(json_encode($array));
                    if (!empty($array)) {
                        slackInfo($array, __CLASS__);
                    }
                    //
                }
                $oReservation->refresh();
            }

            if ($this->payout && $oReservation->fromApp()) {
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
                $oReservation->refresh();
            }

            $bar->advance();
        }
        $bar->finish();
        $this->finish();
    }
}
