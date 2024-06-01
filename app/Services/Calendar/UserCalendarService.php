<?php

declare(strict_types=1);

namespace App\Services\Calendar;

use App\Http\Transformers\Api\ListingCalendarDateTransformer;
use App\Models\Listing;
use App\Models\User;
use App\Models\UserCalendar;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserCalendarService
{
    /**
     *
     */
    const KEY_DATE_FORMAT = 'Y-m-d';

    /**
     * @var User
     */
    private $oUser;

    /**
     * @var Listing|null
     */
    private $oListing;

    /**
     * @var array
     */
    private $aDates = [];

    /**
     * UserCalendarService constructor.
     * @param User $oUser
     */
    public function __construct(User $oUser)
    {
        $this->oUser = $oUser;
    }

    /**
     * @param Listing $oListing
     * @return $this
     */
    public function setListing(Listing $oListing)
    {
        $this->oListing = $oListing;

        $oDates = $this->oListing
            ->calendarDates()
            ->where('user_id', $this->oUser->id)
            ->active()
            ->ordered()
            ->get(['date_at', 'type']);
            //->toArray();

        $aDates = [];
        foreach ($oDates as $oDate) {
            $aDates[$oDate->date_at->format(self::KEY_DATE_FORMAT)][] = $oDate->type;
        }
        $this->aDates = $aDates;
        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function dates()
    {
        $data = [];
        $months = $this->getMonthsByCurrent();
        foreach ($months as $month) {
            $data[] = [
                'title' => $month->format('F Y'),
                'weeks' => $this->getDays($month),
            ];
        }
        return $data;
    }

    /**
     * @return array
     */
    private function getMonthsByCurrent()
    {
        $date = now();
        if ($date->copy()->subWeeks(1)->month === $date->month) {
            return [
                $date->copy()->startOfMonth(),
                $date->copy()->addMonthsNoOverflow(1)->startOfMonth(),
                $date->copy()->addMonthsNoOverflow(2)->startOfMonth(),
                $date->copy()->addMonthsNoOverflow(3)->startOfMonth(),
            ];
        } else {
            return [
                $date->copy()->subMonthsNoOverflow(1)->startOfMonth(),
                $date->copy()->startOfMonth(),
                $date->copy()->addMonthsNoOverflow(1)->startOfMonth(),
                $date->copy()->addMonthsNoOverflow(2)->startOfMonth(),
            ];
        }
    }

    /**
     * @param Carbon $current
     * @return array
     * @throws \Exception
     */
    private function getDays(Carbon $current)
    {
        $start = $current->copy()->startOfMonth();
        $end = $current->copy()->endOfMonth();
        $dates = [];
        $start = $this->getStart($start);
        $end = $this->getEnd($end);
        $weeks = [];

        while ($start->lte($end)) {
            // weekOfYear возвращает с понедельника
            // поэтому добавляется число и если последняя неделя, то на 1
            if ($start->isSunday()) {
                $key = $start->weekOfYear + 1;
                if ($key > now()->endOfYear()->weekOfYear) {
                    $key = 1;
                }
            } else {
                $key = $start->weekOfYear;
            }
            //$keyInWeek = $start->format('l');
            //$keyInWeek = $start->copy()->format(self::KEY_DATE_FORMAT);
            if (!in_array($key, $weeks)) {
                $weeks[] = $key;
            }
            $key = array_search($key, $weeks);
            //$key = $start->copy()->format('Y-m-d');
            if ($start->month !== $current->month) {
                //$dates[$key][$keyInWeek] = null;
                $dates[$key][] = null;
            } else {
                $dates[$key][] = $this->checkUserDate($start, $current);
            }
            $start->addDay();
        }
        return $dates;
    }

    /**
     * @param Carbon $end
     * @return Carbon
     * @throws \Exception
     */
    private function getEnd(Carbon $end)
    {
        $timeEnd = strtotime('next sunday', strtotime($end->format(self::KEY_DATE_FORMAT)));
        $end = new Carbon(date(self::KEY_DATE_FORMAT, $timeEnd));
        $end->subDay();
        return $end;
    }

    /**
     * @param Carbon $start
     * @return Carbon
     * @throws \Exception
     */
    private function getStart(Carbon $start)
    {
        if (!$start->isSunday()) {
            $timeStart = strtotime('previous sunday', strtotime($start->format(self::KEY_DATE_FORMAT)));
            $start = new Carbon(date(self::KEY_DATE_FORMAT, $timeStart));
        }
        return $start;
    }

    /**
     * @param Carbon $date
     * @param Carbon $current
     * @return array
     */
    private function checkUserDate(Carbon $date, Carbon $current)
    {
        $dateKey = $date->format(self::KEY_DATE_FORMAT);
        $defaultStatus = UserCalendar::TYPE_AVAILABLE;

        $aType = isset($this->aDates[$dateKey])
            ? $this->aDates[$dateKey]
            : [$defaultStatus];

        $count = count($aType);
        $type = $aType[0];

        return (new ListingCalendarDateTransformer())->transform($date, $type, $count);
    }
}
