<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Events\Reservation\ReservationSuccessEvent;
use App\Events\Reservation\ReservationSyncToEvent;
use App\Models\Balance;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\PaymentCharge;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserCalendar;
use App\Services\Hostfully\HostfullyLeadsService;
use App\Services\Model\Reservation\ReservationPaymentTrait;
use App\Services\Model\Reservation\ReservationPayoutTrait;
use App\Services\Model\Reservation\ReservationRefundTrait;
use App\Services\Model\Reservation\ReservationTransferTrait;
use App\Services\Payment\Stripe\PaymentIntendService;
use App\Services\Payment\Stripe\PaymentPayoutService;
use App\Services\Payment\Stripe\PaymentRefundService;
use App\Services\Payment\Stripe\PaymentTransferService;
use App\Services\Transaction\Transaction;
use Carbon\Carbon;
use Stripe\PaymentIntent;
use Stripe\Payout as PayoutIntend;
use Stripe\Transfer;

class ReservationServiceModel
{
    use ReservationPaymentTrait;
    use ReservationTransferTrait;
    use ReservationPayoutTrait;
    use ReservationRefundTrait;

    /**
     * @var Reservation
     */
    private $oReservation;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string|null
     */
    private $cancelledType = null;

    /**
     * @var bool
     */
    private $sync;

    /**
     * ReservationServiceModel constructor.
     * @param Reservation $oReservation
     */
    public function __construct(Reservation $oReservation)
    {
        $this->oReservation = $oReservation;
        $this->sync = config('hostfully.enabled');
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setCancelledType(string $type)
    {
        $this->cancelledType = $type;
        return $this;
    }

    /**
     * @return $this
     */
    public function setSyncDisabled()
    {
        $this->sync = false;
        return $this;
    }

    /**
     *
     */
    public function eventSync()
    {
        if ($this->sync) {
            event((new ReservationSyncToEvent($this->oReservation)));
        }
    }

    /**
     * @return bool
     */
    public function checkBeforePayment()
    {
        try {
            if (!$this->checkListing()) {
                return false;
            }
            if (!$this->checkHost()) {
                return false;
            }
//            if (!$this->checkHostBalance()) {
//                return false;
//            }
            if (!$this->checkReservation()) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function checkHostBalance()
    {
        $oHost = $this->oReservation->listing->user ?? null;
        $oBalance = $oHost->balance;
        if (is_null($oBalance)) {
            // создание баланса идет после подсчета
            //throw new \Exception('Host balance not found');
        }
        if (!is_null($oBalance) && !$oBalance->isActive()) {
            throw new \Exception('Host balance is not active');
        }
        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function checkReservation()
    {
        if ($this->oReservation->isDeclined()) {
            throw new \Exception('Reservation was declined');
        }
        if ($this->oReservation->isCancelled()) {
            throw new \Exception('Reservation was cancelled');
        }
        if (!is_null($this->oReservation->payment)) {
            throw new \Exception('Reservation has payment');
        }
        if ($this->oReservation->status === Reservation::STATUS_NOT_ACTIVE) {
            throw new \Exception('Reservation was deactivated');
        }
        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function checkHost()
    {
        $oHost = $this->oReservation->listing->user ?? null;
        if (is_null($oHost)) {
            throw new \Exception('Host not found');
        }
        if ($oHost->isBanned()) {
            throw new \Exception('Host is banned');
        }
        if (!$oHost->isActive()) {
            throw new \Exception('Host is not active');
        }
        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function checkListing()
    {
        $oListing = $this->oReservation->listing ?? null;
        if (is_null($oListing)) {
            throw new \Exception('Listing not found');
        }
        if ($oListing->isBanned()) {
            throw new \Exception('Listing is banned');
        }
        if (!$oListing->isActive()) {
            throw new \Exception('Listing is not active');
        }
        return true;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @throws \Exception
     */
    public function setCancelled()
    {
        // юзерне может отменить уже идущую бронь
        if ($this->oReservation->isBeginning()) {
            throw new \Exception('You cannot cancel the reservation that has already started');
        }
        if ($this->oReservation->fromApp()) {
            $this->cancelPayment();
        }
        $this->oReservation->update([
            'status' => Reservation::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancelled_type' => $this->cancelledType,
        ]);
        $this->clearReservationCalendarDate();
        $this->eventSync();
    }

    /**
     * Если удаляется юзер или листинг, на которого есть будущая бронь, то она отменяется, отмена
     * Бронь становится не активной
     */
    public function setNotActive()
    {
        $this->setDeclined();
        $this->oReservation->update([
            'status' => Reservation::STATUS_NOT_ACTIVE,
        ]);
        //$this->eventSync();
    }

    /**
     * @throws \Exception
     */
    public function forceSetCancel()
    {
        // юзерне может отменить уже идущую бронь
        if ($this->oReservation->isBeginning()) {
            throw new \Exception('You cannot cancel the reservation that has already started');
        }
        $this->cancelPayment(true);
        $this->oReservation->update([
            'status' => Reservation::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancelled_type' => $this->cancelledType,
        ]);
        $this->clearReservationCalendarDate();
        $this->eventSync();
    }

    /**
     * @throws \Exception
     */
    public function setDeclined()
    {
        // юзерне может отменить уже идущую бронь
        if ($this->oReservation->isBeginning()) {
            throw new \Exception('You cannot cancel the reservation that has already started');
        }
        // @todo если хост отменяет, то не оставлять service_fee
        if ($this->oReservation->fromApp()) {
            $this->cancelPayment(true);
        }
        $this->oReservation->update([
            'status' => Reservation::STATUS_DECLINED,
            'declined_at' => now(),
            'cancelled_type' => $this->cancelledType,
        ]);
        $this->clearReservationCalendarDate();
        $this->eventSync();
    }

    /**
     *
     */
    public function setAccepted()
    {
        $this->oReservation->update([
            'accepted_at' => now(),
            'status' => Reservation::STATUS_ACCEPTED,
        ]);
        // если из хостхулли бы отменен, а потом опять статус на нормальный поменялся, то сбросить даты
        if ($this->oReservation->fromHostfully()) {
            $this->oReservation->update([
                'cancelled_at' => null,
                'declined_at' => null,
                'cancelled_type' => null,
            ]);
        }
        $this->eventSync();
    }

//
//    /**
//     * @param User $oUser
//     * @param User $oHost
//     * @param string $token
//     * @return \App\Models\Payment
//     * @throws \Stripe\Exception\ApiErrorException
//     */
//    public function paymentByToken(User $oUser, User $oHost, string $token)
//    {
//        // совершение платежа
//        $payment = (new StripePaymentService())
//            ->setUser($oUser)
//            ->setReservation($this->oReservation)
//            ->setCardByToken($token)
//            ->makePayment();
//
//        // сохранение платежки, обновление баланса
//        return $this->afterPayment($oUser, $oHost, $payment);
//    }



    /**
     * @param User $oUser
     * @return bool
     */
    public function canDecline(User $oUser)
    {
        if ($this->oReservation->isDeclined()) {
            $this->message = 'Cannot cancel reservation. Reservation was cancelled before.';
            return false;
        }
        if ($this->oReservation->isCancelled()) {
            $this->message = 'Cannot cancel reservation. Reservation was cancelled before.';
            return false;
        }
        if ($this->oReservation->isPassed()) {
            $this->message = 'Cannot cancel reservation. Reservation was cancelled before.';
            return false;
        }
        $oListing = $this->oReservation->listing;
        if (is_null($oListing)) {
            $this->message = 'Cannot cancel reservation. Access denied.';
            return false;
        }
        // если бронь не из стейменити, а сихронизировалась из hostfully
        if ($this->oReservation->source === Reservation::SOURCE_HOSTFULLY) {
            $this->message = 'Cannot cancel reservation. Reservation has been synced. Please cancel from Hostfully.';
            return false;
        }
        // только если листинг этого пользователя
        if ($oListing->user_id === $oUser->id) {
            return true;
        }
        $this->message = 'Cannot cancel reservation. Access denied.';
        return false;
    }

    /**
     * @param User $oUser
     * @return bool
     */
    public function canCancel(User $oUser)
    {
        if ($this->oReservation->isDeclined()) {
            $this->message = 'Cannot cancel reservation. Reservation was cancelled before.';
            return false;
        }
        if ($this->oReservation->isCancelled()) {
            $this->message = 'Cannot cancel reservation. Reservation was cancelled before.';
            return false;
        }
        if ($this->oReservation->isPassed()) {
            $this->message = 'Cannot cancel reservation. Reservation is passed.';
            return false;
        }
        // если бронь не из стейменити, а сихронизировалась из hostfully
        if ($this->oReservation->source === Reservation::SOURCE_HOSTFULLY) {
            $this->message = 'Cannot cancel reservation. Reservation has been synced. Please cancel from Hostfully.';
            return false;
        }
        // только если это его бронь
        if ($this->oReservation->user_id === $oUser->id) {
            return true;
        }
        $this->message = 'Cannot cancel reservation. Access denied.';
        return false;
    }

    /**
     * @param User $oUser
     * @return bool
     */
    public function canLeaveReview(User $oUser)
    {
        return true;
    }

    /**
     * От гостя листингу
     *
     * @return bool
     */
    public function hasReviewFromGuest()
    {
        $count = $this->oReservation
            ->reviews()
            ->where('user_id', $this->oReservation->user_id)
            ->where('reservation_id', $this->oReservation->id)
            ->where('reviewable_id', $this->oReservation->listing_id)
            ->where('reviewable_type', Listing::class)
            ->count();
        return $count !== 0;
    }

    /**
     * От хоста гостю
     *
     * @return bool
     */
    public function hasReviewFromHost()
    {
        $count = $this->oReservation
            ->reviews()
            ->where('user_id', $this->oReservation->listing->user_id)
            ->where('reservation_id', $this->oReservation->id)
            ->where('reviewable_id', $this->oReservation->user_id)
            ->where('reviewable_type', User::class)
            ->count();
        return $count !== 0;
    }


    /**
     * @param User $oUser
     * @param Listing $oListing
     * @param array $data
     * @return Transaction
     */
    public function setReviewByGuest(User $oUser, Listing $oListing, array $data)
    {
        return $this->leaveReview($oUser, $oListing, $data);
    }

    /**
     * @param User $oUser
     * @param User $oGuest
     * @param array $data
     * @return Transaction
     */
    public function setReviewByHost(User $oUser, User $oGuest, array $data)
    {
        return $this->leaveReview($oUser, $oGuest, $data);
    }

    /**
     * @param User $oUser
     * @param Listing|User $oItem
     * @param array $data
     * @return Transaction
     */
    private function leaveReview(User $oUser, $oItem, array $data)
    {
        return transaction()->commitAction(function () use ($oUser, $oItem, $data) {
            $oItem->reviews()->create([
                'user_id' => $oUser->id,
                'reservation_id' => $this->oReservation->id,
                'rating' => (float)$data['rating'],
                'description' => $data['description'],
                'published_at' => now(),
            ]);
        });
    }

    /**
     *
     */
    public function clearReservationCalendarDate()
    {
        $this->clearReservationUserCalendar();
//
//        /** @var UserCalendar|null $oUserCalendar */
//        $oUserCalendar = $this->oReservation->userCalendar()
//            ->booked()
//            ->whereDate('date_at', $this->oReservation->start_at->format('Y-m-d'))
//            ->first();
//        if (!is_null($oUserCalendar)) {
//            try {
//                $oUserCalendar->delete();
//            } catch (\Exception $e) {
//                //
//            }
//        }
    }

    /**
     * @throws \Exception
     */
    public function clearReservationUserCalendar()
    {
        /** @var UserCalendar[] $oUserCalendars */
        $oUserCalendars = $this->oReservation->userCalendar()
            ->booked()
            ->get();
        foreach ($oUserCalendars as $oUserCalendar) {
            $oUserCalendar->delete();
        }
    }

    /**
     *
     */
    public function updateTimezone()
    {
        $this->oReservation->update([
            'timezone' => $this->oReservation->listing->timezone ?? null,
        ]);
    }

    /**
     *
     */
    public function updateServerDatesByTimezone()
    {
        $this->oReservation->update([
            'server_start_at' => Carbon::parse($this->oReservation->start_at->toDateTimeString(), $this->oReservation->timezone)
                ->copy()
                ->timezone(config('app.timezone')),
            'server_finish_at' => Carbon::parse($this->oReservation->finish_at->toDateTimeString(), $this->oReservation->timezone)
                ->copy()
                ->timezone(config('app.timezone')),
        ]);
    }

    /**
     * @return \App\Services\Transaction\Transaction
     */
    public function delete()
    {
        return transaction()->commitAction(function () {
            $oReservation = $this->oReservation;
            if (!is_null($oReservation->payment)) {
                $oReservation->payment->delete();
            }
            if (!is_null($oReservation->transfer)) {
                $oReservation->transfer->delete();
            }
            if (!is_null($oReservation->payout)) {
                $oReservation->payout->delete();
            }
            slackInfo($oReservation->id, 'Reservation DELETE');
            $oReservation->delete();
            return null;
        });
    }


    /**
     * @return string|null
     */
    public function getCancelledTypeText()
    {
        switch ($this->oReservation->cancelled_type) {
            case Reservation::CANCELLED_TYPE_BY_HOST:
                return 'Cancelled by Host';
            case Reservation::CANCELLED_TYPE_BY_GUEST:
                return 'Cancelled by Guest';
            case Reservation::CANCELLED_TYPE_BY_ADMIN:
                return 'Cancelled by Admin';
            case Reservation::CANCELLED_TYPE_BY_USER_DELETED:
                return 'Cancelled by user was deleted';
            case Reservation::CANCELLED_TYPE_BY_LISTING_DELETED:
                return 'Cancelled by listing was deleted';
        }
        return null;
    }

    /**
     * @param Listing $oListing
     * @param Carbon $date
     * @param string $type
     */
    public function setToCalendar(Listing $oListing, Carbon $date, string $type = UserCalendar::TYPE_BOOKED)
    {
        $oHost = $oListing->user;
        if (!is_null($oHost)) {
            (new UserCalendarServiceModel($oHost, $oListing, $this->oReservation))
                ->setByDate($type, $date);
        }
    }

    /**
     * @param Listing $oListing
     * @param Carbon $start_at
     * @param Carbon $finish_at
     */
    public function setToCalendarStartFinishFull(Listing $oListing, Carbon $start_at, Carbon $finish_at)
    {
        $start = $start_at->copy();
        $end = $finish_at->copy();

        /** @var Carbon[] $dates */
        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }
        //slackInfo($dates);
        foreach ($dates as $date) {
            $this->setToCalendar($oListing, $date, UserCalendar::TYPE_BOOKED_FULL);
        }
    }
}
