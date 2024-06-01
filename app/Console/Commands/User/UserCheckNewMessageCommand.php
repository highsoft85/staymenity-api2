<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Console\Commands\Common\CommandTrait;
use App\Jobs\Mail\User\SendMailUserHaveNewMessageJob;
use App\Jobs\QueueCommon;
use App\Models\ChatMessage;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\HaveNewMessageNotification;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Logger\Logger;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserCheckNewMessageCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const DELAY_MINUTES = 30;

    /**
     *
     */
    const SIGNATURE = 'user:check-new-message';

    /**
     * The name and signature of the console command.
     *
     * php artisan user:check-new-message
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
    protected $description = 'Проверка на непрочитанные сообщения';

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

        $now = now();
        //slackInfo([], __CLASS__);
        $aUsers = [];

        User::active()->whereHas('chatsActive')->orderBy('id')->chunk(100, function ($oUsers) use ($now, &$aUsers) {
            foreach ($oUsers as $oUser) {
                /** @var User $oUser */

                $aIdChats = $oUser->chatsActive()->pluck('id')->toArray();

                //
                $oMessages = ChatMessage::active()
                    ->whereIn('chat_id', $aIdChats)
                    ->where('user_id', '<>', $oUser->id)
                    ->whereNull('read_at')
                    ->whereNull('new_message_mailed_at')
                    ->where('send_at', '<', $now->copy()->subMinutes(self::DELAY_MINUTES)) // если дата отправки была больше 30 минут назад
                    ->get();

                if ($oMessages->count() !== 0) {
                    $this->clearMessages($oUser, $oMessages, $now);
                    $aUsers[] = $oUser->id;
                }
            }
        });

        // отправка сообщений
        $aUsers = array_unique($aUsers);
        if (!empty($aUsers)) {
            $this->log(json_encode($aUsers));
            slackInfo($aUsers, __CLASS__);
            foreach ($aUsers as $id) {
                $oUser = User::find($id);
                if (!is_null($oUser)) {
                    $this->sendNotification($oUser);
                }
            }
        }

        $this->finish();
    }

    /**
     * @param User $oUser
     */
    private function sendNotification(User $oUser)
    {
        if (QueueCommon::commandMailIsEnabled()) {
            SendMailUserHaveNewMessageJob::dispatch($oUser)->onQueue(QueueCommon::QUEUE_NAME_MAIL);
        } else {
            SendMailUserHaveNewMessageJob::dispatchNow($oUser);
        }
    }

    /**
     * @param User $oUser
     * @param ChatMessage[]|mixed $oMessages
     * @param Carbon $now
     */
    private function clearMessages(User $oUser, $oMessages, Carbon $now)
    {
        foreach ($oMessages as $oMessage) {
            $oMessage->update([
                'new_message_mailed_at' => $now,
            ]);
        }
        $array = $oMessages->pluck('id')->toArray();
        $this->log(json_encode($array));
        slackInfo($array, __CLASS__);
    }
}
