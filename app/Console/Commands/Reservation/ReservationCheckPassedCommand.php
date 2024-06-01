<?php

declare(strict_types=1);

namespace App\Console\Commands\Reservation;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\LeaveReviewNotification;
use App\Notifications\User\Reservation\ReservationPayoutNotification;
use App\Services\Logger\Logger;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ReservationCheckPassedCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'reservation:check-passed';

    /**
     * The name and signature of the console command.
     *
     * php artisan reservation:check-passed
     * php artisan user:create --testing
     *
     * @var string
     */
    protected $signature = self::SIGNATURE . '
        {--payout=true : по умолчанию включены выводы}
        {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Поиск прошедших бронирований';

    /**
     * @var bool
     */
    private $log = false;

    /**
     * @var bool
     */
    private $payout = true;

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
        $this->logger = (new Logger())->setName('reservations/passed')->log();
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

        // начало, т.е. 09:58
        // конец, т.е. 10:00
        $hourBetween = [
            now()->startOfHour()->subMinutes(2),
            now()->startOfHour(),
        ];

        /** @var Reservation[] $oReservations */
        $oReservations = Reservation::whereNotNull('accepted_at')
            ->where('status', Reservation::STATUS_ACCEPTED)
            ->whereBetween('server_finish_at', $hourBetween)
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
            $oReservation->update([
                'passed_at' => $now,
                'beginning_at' => null,
            ]);

            $oHost = $oReservation->listing->user ?? null;
            $oGuest = $oReservation->user ?? null;
            if (!is_null($oHost)) {
                $type = ReviewServiceModel::TYPE_TO_HOST;
                $oHost->notify(new LeaveReviewNotification($oReservation, $type, $oGuest));
            }
            if (!is_null($oGuest)) {
                $type = ReviewServiceModel::TYPE_TO_GUEST;
                $oGuest->notify(new LeaveReviewNotification($oReservation, $type, $oHost));
            }

            if ($this->payout && $oReservation->fromApp()) {
                // создание выплаты
                try {
                    (new ReservationServiceModel($oReservation))->makePayout();
                    $oHost = $oReservation->listing->user;
                    $oHost->notify(new ReservationPayoutNotification($oReservation));
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
            }
            $bar->advance();
        }
        $bar->finish();
        $this->finish();
    }
}
