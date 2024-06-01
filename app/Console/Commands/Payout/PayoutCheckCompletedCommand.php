<?php

declare(strict_types=1);

namespace App\Console\Commands\Payout;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Payout;
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

class PayoutCheckCompletedCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'payout:check-completed';

    /**
     * The name and signature of the console command.
     *
     * php artisan payout:check-completed
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
    protected $description = 'Проверка выплат';

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
        $this->logger = (new Logger())->setName('payouts')->log();
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

        /** @var Payout[] $oPayouts */
        $oPayouts = Reservation::beginning()
            ->whereNotNull('user_id')
            ->get();

        $array = $oPayouts->pluck('id')->toArray();
        $this->log(json_encode($array));
        if (!empty($array)) {
            slackInfo($array, __CLASS__);
        }

        $bar = $this->bar(count($oPayouts));
        foreach ($oPayouts as $oPayout) {
            $oUser = $oPayout->user;
            $payout = (new PaymentPayoutService())->setUser($oUser)->getPayout($oPayout->provider_payout_id);
            if ($payout->status === 'paid') {
                $oPayout->update([
                    'status' => Payout::STATUS_COMPLETED,
                ]);
            }
        }
        $bar->finish();
        $this->finish();
    }
}
