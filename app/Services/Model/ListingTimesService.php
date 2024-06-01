<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\Listing;
use App\Models\ListingTime;
use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ListingTimesService
{
    /**
     * @param Listing $oItem
     * @param Carbon $date
     * @return array
     */
    public function getTimes(Listing $oItem, Carbon $date)
    {
        $times = [
            'locked' => [],
        ];

        $locked = [];

        if ($date->isWeekend()) {
            /** @var ListingTime[] $oTimes */
            $oTimes = $oItem
                ->times()
                ->weekends()
                ->orderBy('id', 'asc')
                ->get();

            $locked = $this->getLockedTimes($oTimes, $date);
        } elseif ($date->isWeekday()) {
            /** @var ListingTime[] $oTimes */
            $oTimes = $oItem
                ->times()
                ->weekdays()
                ->orderBy('id', 'asc')
                ->get();

            $locked = $this->getLockedTimes($oTimes, $date);
        }

        $locked = $this->rejectReservations($oItem, $date, $locked);
        $locked = $this->rejectReservationsBetween($oItem, $date, $locked);

        $times['locked'] = $locked;
        return $times;
    }

    /**
     * @param ListingTime[] $oTimes
     * @param Carbon $date
     * @return array
     */
    private function getLockedTimes($oTimes, Carbon $date)
    {
        $default = [
            'from' => $this->getParseDay($date, '09:00 AM'),
            'to' => $this->getParseDay($date, '10:00 PM'),
        ];

        $rules = [];
        foreach ($oTimes as $key => $oTime) {
            $rules[$key]['from'] = $this->getParseDay($date, $oTime->from);
            $rules[$key]['to'] = $this->getParseDay($date, $oTime->to);
        }

        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();
        $hours = [];

        while ($start->lte($end)) {
            $isBetween = $this->checkTime($start, $default, $rules);

            // если нужны только заблокированные
            if (!$isBetween) {
                $value = $this->timeFormat($start);
                $hours[] = $value;
            }
            // нужны все
            //$hours['_' . $start->format('H')] = $isBetween;

            $start->addHour();
        }
        return $hours;
    }

    /**
     * @param Carbon $date
     * @param string $time
     * @return Carbon
     */
    private function getParseDay(Carbon $date, string $time)
    {
        return Carbon::parse($date->format('Y-m-d') . ' ' . $time);
    }

    /**
     * @param Carbon $start
     * @param array $default
     * @param array $rules
     * @return bool
     */
    private function checkTime(Carbon $start, array $default, array $rules)
    {
        $isBetween = false;
        if (!empty($rules)) {
            // если хоть одно совпадает, то ок
            foreach ($rules as $rule) {
                if ($start->between($rule['from'], $rule['to'])) {
                    if ($start->diffInSeconds($rule['to']) !== 0) {
                        $isBetween = true;
                        break;
                    }
                }
            }
        } else {
            if ($start->between($default['from'], $default['to'])) {
                $isBetween = true;
            }
        }
        return $isBetween;
    }

    /**
     * @param Carbon $date
     * @return int
     */
    private function timeFormat(Carbon $date)
    {
        return (int)$date->format('H');
    }

    /**
     * @param Listing $oItem
     * @param Carbon $date
     * @param array $lockedHours
     * @return array
     */
    private function rejectReservations(Listing $oItem, Carbon $date, array $lockedHours)
    {
        /** @var Reservation[] $oReservations */
        $oReservations = $oItem->reservations()->whereBetween('start_at', [
            $date->copy()->startOfDay(),
            $date->copy()->endOfDay(),
        ])->whereIn('status', [
            Reservation::STATUS_DRAFT,
            Reservation::STATUS_PENDING,
            Reservation::STATUS_ACCEPTED,
        ])->get();

        return $this->reservationsToLockedHours($oReservations, $lockedHours);
    }

    /**
     * Для hostfully, т.к. там брони по несколько дней, а не по часам
     *
     * @param Listing $oItem
     * @param Carbon $date
     * @param array $lockedHours
     * @return array
     */
    private function rejectReservationsBetween(Listing $oItem, Carbon $date, array $lockedHours)
    {
        /** @var Reservation[] $oReservations */
        $oReservations = $oItem->reservations()
            ->whereRaw('? between start_at and finish_at', [$date->copy()->startOfDay()])
            ->whereIn('status', [
                Reservation::STATUS_DRAFT,
                Reservation::STATUS_PENDING,
                Reservation::STATUS_ACCEPTED,
            ])->get();

        return $this->reservationsToLockedHours($oReservations, $lockedHours);
    }

    /**
     * @param Reservation[] $oReservations
     * @param array $lockedHours
     * @return array
     */
    private function reservationsToLockedHours($oReservations, array $lockedHours)
    {
        $newLockedHours = [];
        // поиск по броням и берем времена
        foreach ($oReservations as $oReservation) {
            $start = $oReservation->start_at;
            $end = $oReservation->finish_at;
            while ($start->lte($end)) {
                $newLockedHours[] = $this->timeFormat($start);
                $start->addHour();
            }
        }

        // если их нет, то добавляем
        foreach ($newLockedHours as $hour) {
            if (!in_array($hour, $lockedHours)) {
                $lockedHours [] = $hour;
            }
        }
        sort($lockedHours);
        return $lockedHours;
    }
}
