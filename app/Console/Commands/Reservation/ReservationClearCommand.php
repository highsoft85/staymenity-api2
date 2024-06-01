<?php

declare(strict_types=1);

namespace App\Console\Commands\Reservation;

use App\Console\Commands\Common\CommandTrait;
use App\Events\Reservation\ReservationSyncToEvent;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Logger\Logger;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ReservationClearCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'reservation:clear';

    /**
     * The name and signature of the console command.
     *
     * php artisan reservation:clear
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
    protected $description = 'Очистка броней без оплаты';

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
        $this->logger = (new Logger())->setName('reservations/clear')->log();
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

        /** @var Reservation[] $oReservations */
        $oReservations = Reservation::whereNull('payment_id')
            ->whereIn('status', [
                Reservation::STATUS_DRAFT,
                Reservation::STATUS_PENDING,
            ])
            ->where('created_at', '<', $now->copy()->subMinutes(Reservation::PAYMENT_TIMEOUT))
            ->get();

        $array = $oReservations->pluck('id')->toArray();
        $this->log(json_encode($array));
        if (!empty($array)) {
            slackInfo($array, __CLASS__);
        }

        $bar = $this->bar(count($oReservations));
        foreach ($oReservations as $oReservation) {
            $oReservation->update([
                'declined_at' => $now,
                'status' => Reservation::STATUS_NOT_ACTIVE,
            ]);
            (new ReservationServiceModel($oReservation))
                ->clearReservationCalendarDate();

            if (!is_null($oReservation->hostfully) && config('hostfully.enabled')) {
                event((new \App\Events\Reservation\ReservationSyncToEvent($oReservation)));
            }
            $bar->advance();
        }
        $bar->finish();
        $this->finish();
    }
}
