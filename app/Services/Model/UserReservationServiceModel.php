<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Events\Auth\RegisteredEvent;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserCalendar;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserReservationServiceModel
{
    const DATE_FORMAT = 'Y-m-d H:i';
    const CODE_LENGTH = 10;

    /**
     * @var User|null
     */
    private $oUser;

    /**
     * @var Listing
     */
    private $oListing;

    /**
     * @var Reservation
     */
    private $oReservation;

    /**
     * @var Carbon
     */
    private $date;

    /**
     * UserCalendarService constructor.
     * @param Listing $oListing
     * @param User|null $oUser
     */
    public function __construct(Listing $oListing, ?User $oUser = null)
    {
        $this->oUser = $oUser;
        $this->oListing = $oListing;
    }

    /**
     * @param array $data
     * @param string $timezone
     * @return Carbon
     */
    private function getStartAt(array $data, string $timezone)
    {
        return Carbon::createFromFormat(self::DATE_FORMAT, $data['start_at'], $timezone)->startOfHour();
    }

    /**
     * @param array $data
     * @param string $timezone
     * @return Carbon
     */
    private function getFinishAt(array $data, string $timezone)
    {
        return Carbon::createFromFormat(self::DATE_FORMAT, $data['finish_at'], $timezone)->endOfHour();
    }

    /**
     * @param array $data
     * @return $this
     * @throws \Exception
     */
    public function create(array $data)
    {
        $start_at = $this->getStartAt($data, $this->oListing->timezone);
        $finish_at = $this->getFinishAt($data, $this->oListing->timezone);

        if (is_null($this->oUser)) {
            $this->oUser = (new UserServiceModel())->create($data);
            $this->oUser->assignRole(User::ROLE_GUEST);
            $this->oUser->update([
                'register_by' => User::REGISTER_BY_RESERVATION,
                'current_role' => User::ROLE_GUEST,
                'phone_verified_at' => now(),
            ]);
            $oService = (new UserServiceModel($this->oUser));
            $oService->saveUserTimezoneByIp();
            $oService->afterRegister();
        }

        $this->setDate($start_at);

        $hours = $this->getHours($start_at, $finish_at);
        $amount = $this->getAmount($hours);

        $serviceFee = $this->getServiceFeeByListing($this->oListing);

        $totalAmount = $this->getTotalAmount($amount, $serviceFee);

        /** @var Reservation oReservation */
        $this->oReservation = $this->oUser->reservations()->create([
            'listing_id' => $this->oListing->id,
            'guests_size' => $data['guests_size'] ?? null,
            'total_price' => $totalAmount,
            'price' => $amount,
            'service_fee' => $serviceFee,
            'message' => $data['message'] ?? null,
            'server_start_at' => $start_at->copy()->timezone(config('app.timezone')),
            'server_finish_at' => $finish_at->copy()->timezone(config('app.timezone')),
            'start_at' => $start_at,
            'finish_at' => $finish_at,
            'is_agree' => 1,
            'code' => Str::upper(Str::random(self::CODE_LENGTH)),
            'status' => Reservation::STATUS_PENDING,
            'timezone' => $this->oListing->timezone,
        ]);
        $this->setToCalendar($this->oReservation);
        $this->setFreeCancellation($this->oReservation);

        return $this;
    }

    /**
     * @param array $data
     * @param string $timezone
     * @return bool
     */
    public function checkTimesByData(array $data, string $timezone)
    {
        $start_at = $this->getStartAt($data, $timezone);
        $finish_at = $this->getFinishAt($data, $timezone);

        return $this->checkTimes($start_at, $finish_at);
    }

    /**
     * @param int $hours
     * @return float
     */
    private function getAmount(int $hours)
    {
        $value = $this->oListing->price * $hours;
        return (float)$value;
    }

    /**
     * @param Listing $oListing
     * @return int
     */
    private function getServiceFeeByListing(Listing $oListing)
    {
        return $oListing->isFreeService() ? 0 : Reservation::SERVICE_FEE;
    }

    /**
     * @param float $amount
     * @param int $serviceFee
     * @return float
     */
    private function getTotalAmount(float $amount, int $serviceFee)
    {
        return $amount + $serviceFee;
    }

    /**
     * @param Carbon $start_at
     * @param Carbon $finish_at
     * @return int
     */
    private function getHours(Carbon $start_at, Carbon $finish_at)
    {
        $finishAt = $finish_at->copy()->addMinute();
        // @todo reservationSub
        //$finish = $finish_at->copy()->subMinute();
        return $start_at->diffInHours($finishAt);
    }


    /**
     * @return Reservation
     */
    public function getReservation()
    {
        return $this->oReservation;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->oUser;
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    public function setDate(Carbon $date)
    {
        $this->date = $date->copy()->startOfDay();
        return $this;
    }

    /**
     * @param Reservation $oReservation
     */
    public function setToCalendar(Reservation $oReservation)
    {
        $oHost = $this->oListing->user;
        if (!is_null($oHost)) {
            (new UserCalendarServiceModel($oHost, $this->oListing, $oReservation))
                ->setByDate(UserCalendar::TYPE_BOOKED, $this->date);
        }
    }

    /**
     * @param Carbon $start_at
     * @param Carbon $finish_at
     * @return bool
     */
    public function checkTimes(Carbon $start_at, Carbon $finish_at)
    {
        $hours = [];

        $start = $start_at->copy();
        $end = $finish_at->copy();
        while ($start->lte($end)) {
            $hours[] = $start->format('Y-m-d H:i:s');
            $start->addHour();
        }

        $oReservations = $this->oListing
            ->reservations()
            ->activeCheckLocked()
            ->whereIn('start_at', $hours)
            ->get();

        if ($oReservations->count() !== 0) {
            return false;
        }

        // если юзер заблочил дату после выбора гостем даты для бронирования
        $hasLocked = $this->oListing
            ->calendarDatesLocked()
            ->whereDate('date_at', $start_at->format('Y-m-d'))
            ->first();

        if (!is_null($hasLocked)) {
            return false;
        }
        return true;
    }

    /**
     * @param Reservation $oReservation
     */
    private function setFreeCancellation(Reservation $oReservation)
    {
        $freeCancellationAt = $oReservation->created_at->copy()->addHours(Reservation::FREE_CANCELLATION);
        $oReservation->update([
            'free_cancellation_at' => $freeCancellationAt,
        ]);
    }

    /**
     * @return bool
     */
    public function checkHostHasPayoutConnect()
    {
        return $this->oListing->user->hasPayoutConnect();
    }

    /**
     * @return Reservation|null
     */
    public function getReservationForChat()
    {
        // (20.02) -> 21.02 -> 22.02
        $oReservation = Reservation::futureNotBeginning()
            ->where('listing_id', $this->oListing->id)
            ->where('user_id', $this->oUser->id)
            ->orderBy('start_at', 'asc')
            ->first();

        if (!is_null($oReservation)) {
            return $oReservation;
        }

        // 20.01 -> 21.01 -> (22.01)
        $oReservation = Reservation::where('listing_id', $this->oListing->id)
            ->where('user_id', $this->oUser->id)
            ->orderBy('start_at', 'desc')
            ->first();

        if (!is_null($oReservation)) {
            return $oReservation;
        }

//        // 20.01 -> 21.01 -> (22.01)
//        // отмененные не показывать
//        $oReservation = Reservation::cancelledOrDeclined()
//            ->where('listing_id', $this->oListing->id)
//            ->where('user_id', $this->oUser->id)
//            ->orderBy('start_at', 'desc')
//            ->first();
//
//        if (!is_null($oReservation)) {
//            return $oReservation;
//        }

        // 20.01 -> 21.01 -> (22.01)
        $oReservation = Reservation::passed()
            ->where('listing_id', $this->oListing->id)
            ->where('user_id', $this->oUser->id)
            ->orderBy('start_at', 'desc')
            ->first();

        if (!is_null($oReservation)) {
            return $oReservation;
        }

        return $oReservation;
    }
}
