<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\UserCalendar;
use App\Services\Calendar\UserCalendarService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class ListingCalendarDateTransformer extends TransformerAbstract
{
    /**
     * @param Carbon $date
     * @param string $type
     * @param int $count
     * @return array
     */
    public function transform(Carbon $date, string $type, int $count)
    {
        $is_disabled = $date->isBefore(now()->startOfDay());

        if ($type === UserCalendar::TYPE_BOOKED_FULL) {
            $type = UserCalendar::TYPE_BOOKED;
        }
        if ($type !== UserCalendar::TYPE_BOOKED) {
            $count = 0;
        }
        return [
            'key' => $date->format(UserCalendarService::KEY_DATE_FORMAT),
            'day' => intval($date->format('d')),
            'week' => Str::lower($date->format('l')),
            'type' => $type,
            'booked_count' => $count,
            'is_disabled' => $is_disabled,
        ];
    }
}
