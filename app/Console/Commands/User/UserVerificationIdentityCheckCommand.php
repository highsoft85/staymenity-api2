<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserIdentity;
use App\Notifications\User\Identity\UserIdentityVerificationStatusNotification;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Logger\Logger;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserIdentityVerificationServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserVerificationIdentityCheckCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'user:verification-identity-check';

    /**
     * The name and signature of the console command.
     *
     * php artisan user:verification-identity-check
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
    protected $description = 'Проверка статуса из autohost';

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
        $this->logger = (new Logger())->setName('users/identities')->log();
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
        $oUsers = User::active()
            ->whereNull('identity_verified_at')
            ->whereHas('identities', function ($q) {
                $q->whereIn('status', [UserIdentity::STATUS_PENDING, UserIdentity::STATUS_QUEUED]);
            })
            ->get();

        $array = $oUsers->pluck('id')->toArray();
        $this->log(json_encode($array));
        if (!empty($array)) {
            //slackInfo($array, __CLASS__);
        }

        $bar = $this->bar(count($oUsers));
        foreach ($oUsers as $oUser) {
            /** @var UserIdentity $oUserIdentity */
            $oUserIdentity = $oUser->identities()->first();
            if (is_null($oUserIdentity)) {
                continue;
            }
            $result = transaction()->commitAction(function () use ($oUserIdentity, $oUser) {
                $oService = (new UserIdentityVerificationServiceModel($oUser, $oUserIdentity));
                $oService->commonCheckStatusAndSaveResults();
                $oUserIdentity->refresh();
                if ($oUserIdentity->status !== UserIdentity::STATUS_PENDING) {
                    $oUser->notify(new UserIdentityVerificationStatusNotification($oUserIdentity));
                }
            });
            if (!$result->isSuccess()) {
                slackInfo($result->getErrorMessage(), __CLASS__);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->finish();
    }
}
