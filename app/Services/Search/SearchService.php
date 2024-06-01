<?php

declare(strict_types=1);

namespace App\Services\Search;

use App\Http\Requests\Api\Search\IndexRequest;
use App\Models\Listing;
use App\Models\Location;
use App\Models\UserCalendar;
use App\Models\UserSave;
use App\Services\Environment;
use App\Services\Geocoder\GeocoderIpService;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\UserServiceModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchService
{
    const FIELD_DATE = 'date';
    const FIELD_GUESTS_SIZE = 'quests_size';
    const FIELD_RENT_TIME_MIN = 'rent_time_min';
    const FIELD_NO_DEPOSIT = 'no_deposit';
    const FIELD_NO_CLEANING_FEE = 'no_cleaning_fee';
    const FIELD_AMENITIES = 'amenities';
    const FIELD_VERIFIED = 'verified';
    const FIELD_HOURS = 'hours';
    const FIELD_RULES = 'rules';
    const FIELD_SIMILAR_ID = 'similar_id';
    const FIELD_MAP = 'map';
    const FIELD_LOCATION = 'location';
    const FIELD_PRICE = 'price';

    const FIELD_USER_SAVE_ID = 'user_save_id';

    const FIELD_MODE = 'mode';
    const MODE_NEARBY = 'nearby';

    /**
     * @param array $data
     * @param IndexRequest|null $request
     * @return Builder|Listing
     */
    public function search(array $data, ?IndexRequest $request = null)
    {
        $oQuery = Listing::active();

        $oQuery->with([
            'type',
            'modelImages',
            'modelImagesOrdered',
            'location',
            'settings',
        ]);

        $oQuery = $this->queryByActiveUser($oQuery);

        if (!isset($data['types_all'])) {
            $data['types_all'] = 0;
        }

        if (isset($data[self::FIELD_MODE]) && !Environment::isDocumentation()) {
            if (!isset($data[self::FIELD_LOCATION]) && !is_null($request)) {
                $oQuery = $this->queryByMode($oQuery, $data[self::FIELD_MODE], $request);
            }
        }

        // поиск по походим
        // если есть, то координаты не нужны, т.к. возьмутся из этого листинга
        if (isset($data[self::FIELD_SIMILAR_ID]) && !Environment::isDocumentation()) {
            $listingId = (int)$data[self::FIELD_SIMILAR_ID];
            $oQuery = $this->queryByListingSimilar($oQuery, $listingId);

            if (!isset($data[self::FIELD_LOCATION]) && !isset($data[self::FIELD_MAP])) {
                /** @var Listing|null $oListing */
                $oListing = Listing::find($listingId);
                if (!is_null($oListing) && !is_null($oListing->location)) {
                    $data[self::FIELD_LOCATION]['latitude'] = $oListing->location->latitude;
                    $data[self::FIELD_LOCATION]['longitude'] = $oListing->location->longitude;
                }
            }
        }

        // поиск по типу
        // если не по всем типам, то идет дальше поиск по массиву
        if (isset($data['types_all']) && (int)$data['types_all'] === 0) {
            if (isset($data['types'])) {
                $oQuery = $this->queryByTypes($oQuery, $data['types']);
            }
        }

        // если нет никаких координат, то будут координаты по дефолту
        if (!isset($data[self::FIELD_MAP]) && !isset($data[self::FIELD_LOCATION]) && !Environment::isDocumentation()) {
            $coordinates = (new UserServiceModel())->defaultCoordinates();
            $lat = $coordinates['latitude'];
            $lon = $coordinates['longitude'];
            $oQuery = $this->queryByLocation($oQuery, $lat, $lon);
        }

        //
        if (isset($data[self::FIELD_LOCATION]) && !isset($data[self::FIELD_MAP]) && !Environment::isDocumentation()) {
            $lat = (float)$data[self::FIELD_LOCATION]['latitude'];
            $lon = (float)$data[self::FIELD_LOCATION]['longitude'];
            $oQuery = $this->queryByLocation($oQuery, $lat, $lon);
        }
        //
        if (isset($data[self::FIELD_MAP]) && !Environment::isDocumentation()) {
            $point1 = [$data[self::FIELD_MAP][0]['latitude'], $data[self::FIELD_MAP][0]['longitude']];
            $point2 = [$data[self::FIELD_MAP][1]['latitude'], $data[self::FIELD_MAP][1]['longitude']];
            $oQuery = $this->queryByMap($oQuery, $point1, $point2);
        }
        //
        if (isset($data[self::FIELD_NO_CLEANING_FEE]) && !Environment::isDocumentation()) {
            $noCleaningFee = (int)$data[self::FIELD_NO_CLEANING_FEE] === 1;
            $oQuery = $this->queryNoCleaningFee($oQuery, $noCleaningFee);
        }
        //
        if (isset($data[self::FIELD_AMENITIES]) && !Environment::isDocumentation()) {
            $amenities = $data[self::FIELD_AMENITIES];
            $oQuery = $this->queryByAmenities($oQuery, $amenities);
        }
        //
        if (isset($data[self::FIELD_RULES]) && !Environment::isDocumentation()) {
            $rules = $data[self::FIELD_RULES];
            $oQuery = $this->queryByRules($oQuery, $rules);
        }
        //
        if (isset($data[self::FIELD_PRICE]) && !Environment::isDocumentation()) {
            $from = (int)$data[self::FIELD_PRICE]['from'];
            $to = (int)$data[self::FIELD_PRICE]['to'];
            $oQuery = $this->queryByPrice($oQuery, $from, $to);
        }
        //
        if (isset($data[self::FIELD_GUESTS_SIZE]) && !Environment::isDocumentation()) {
            $from = (int)$data[self::FIELD_GUESTS_SIZE];
            $oQuery = $this->queryByGuestsSize($oQuery, $from);
        }
        if (isset($data[self::FIELD_RENT_TIME_MIN]) && !Environment::isDocumentation()) {
            $min = (int)$data[self::FIELD_RENT_TIME_MIN];
            $oQuery = $this->queryByRentTimeMin($oQuery, $min);
        }
        if (isset($data[self::FIELD_HOURS]) && !Environment::isDocumentation()) {
            $min = (int)$data[self::FIELD_HOURS];
            $oQuery = $this->queryByRentTimeMin($oQuery, $min);
        }
        if (isset($data[self::FIELD_DATE]) && !Environment::isDocumentation()) {
            $oQuery = $this->queryByDate($oQuery, $data[self::FIELD_DATE]);
        } else {
            $oQuery = $this->queryByDate($oQuery);
        }
        //
        if (isset($data[self::FIELD_NO_DEPOSIT]) && !Environment::isDocumentation()) {
            $noDeposit = (int)$data[self::FIELD_NO_DEPOSIT] === 1;
            $oQuery = $this->queryNoDeposit($oQuery, $noDeposit);
        }
        //
        if (isset($data[self::FIELD_NO_CLEANING_FEE]) && !Environment::isDocumentation()) {
            $noCleaningFee = (int)$data[self::FIELD_NO_CLEANING_FEE] === 1;
            $oQuery = $this->queryNoCleaningFee($oQuery, $noCleaningFee);
        }
        //
        if (isset($data[self::FIELD_VERIFIED]) && !Environment::isDocumentation()) {
            $isVerified = (int)$data[self::FIELD_VERIFIED] === 1;
            $oQuery = $this->queryByVerified($oQuery, $isVerified);
        }

        // поиск по карте внутри сохраненного списка
        if (isset($data[self::FIELD_USER_SAVE_ID]) && !Environment::isDocumentation()) {
            $userSaveId = (int)$data[self::FIELD_USER_SAVE_ID];
            $oQuery = $this->queryByUserSave($oQuery, $userSaveId);
        }

        return $oQuery;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    private function queryByActiveUser(Builder $query)
    {
        return $query->whereHas('user', function (Builder $q) {
            $q->active();
            $q->where('has_payout_connect', 1);
        });
    }


    /**
     * @param Builder $query
     * @param array $aTypes
     * @return Builder
     */
    private function queryByTypes(Builder $query, array $aTypes = [])
    {
        return $query->whereIn('type_id', $aTypes);
    }

    /**
     * @param Builder $query
     * @param float $lat
     * @param float $lon
     * @return Builder
     */
    private function queryByLocation(Builder $query, float $lat, float $lon)
    {
        $point = [$lat, $lon];
        return $query->whereHas('location', function (Builder $q) use ($point) {
            $q->inDistance($point, Location::DEFAULT_DISTANCE);
        });
    }

    /**
     * @param Builder $query
     * @param array $point1
     * @param array $point2
     * @return Builder
     */
    private function queryByMap(Builder $query, array $point1, array $point2)
    {
        return $query->whereHas('location', function (Builder $q) use ($point1, $point2) {
            $q->InContains($point1, $point2);
        });
    }

    /**
     * @param Builder $query
     * @param int $from
     * @param int $to
     * @return Builder
     */
    private function queryByPrice(Builder $query, int $from, int $to)
    {
        return $query->whereBetween('price', [$from, $to]);
    }

    /**
     * @param Builder $query
     * @param int $from
     * @return Builder
     */
    private function queryByGuestsSize(Builder $query, int $from)
    {
        return $query->where('guests_size', '>=', $from);
    }

    /**
     * @param Builder $query
     * @param int $min
     * @return Builder
     */
    private function queryByRentTimeMin(Builder $query, int $min)
    {
        return $query->where('rent_time_min', '<=', $min);
    }

    /**
     * @param Builder $query
     * @param bool $noDeposit
     * @return Builder
     */
    private function queryNoDeposit(Builder $query, bool $noDeposit)
    {
        if ($noDeposit) {
            return $query->whereNull('deposit');
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param bool $noCleaningFee
     * @return Builder
     */
    private function queryNoCleaningFee(Builder $query, bool $noCleaningFee)
    {
        if ($noCleaningFee) {
            return $query->whereNull('cleaning_fee');
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param bool $isVerified
     * @return Builder
     */
    private function queryByVerified(Builder $query, bool $isVerified)
    {
        if ($isVerified) {
            return $query->whereHas('user', function (Builder $q) {
                return $q->whereNotNull('identity_verified_at');
            });
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param array $amenities
     * @return Builder
     */
    private function queryByAmenities(Builder $query, array $amenities)
    {
        if (!empty($amenities)) {
            return $query->whereHas('amenitiesActive', function (Builder $q) use ($amenities) {
                return $q->whereIn('id', $amenities);
            });
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param array $rules
     * @return Builder
     */
    private function queryByRules(Builder $query, array $rules)
    {
        if (!empty($rules)) {
            return $query->whereHas('rulesActive', function (Builder $q) use ($rules) {
                return $q->whereIn('id', $rules);
            });
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $mode
     * @param IndexRequest $request
     * @return Builder
     */
    private function queryByMode(Builder $query, string $mode, IndexRequest $request)
    {
        switch ($mode) {
            case self::MODE_NEARBY:
                $query = $this->queryByModeNearby($query, $request);
                break;
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param IndexRequest $request
     * @return Builder
     */
    private function queryByModeNearby(Builder $query, IndexRequest $request)
    {
        $coordinates = (new UserServiceModel())->defaultCoordinates();
        if (!is_null($coordinates)) {
            $lat = (float)$coordinates['latitude'];
            $lon = (float)$coordinates['longitude'];
            $query = $this->queryByLocation($query, $lat, $lon);
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string|null $date
     * @param string $format
     * @return Builder
     */
    private function queryByDate(Builder $query, ?string $date = null, string $format = 'm-d-Y')
    {
        if (!is_null($date)) {
            $carbonDate = Carbon::createFromFormat($format, $date)->startOfDay();
        } else {
            $carbonDate = now()->addDay()->startOfDay();
        }
        $query = $query->where(function (Builder $q) use ($carbonDate) {
            $q->whereNotExists(function ($q2) use ($carbonDate) {
                $q2
                    ->select(DB::raw(1))
                    ->from('user_calendars')
                    ->whereRaw('user_calendars.listing_id = listings.id')
                    ->where('status', UserCalendar::STATUS_ACTIVE)
                    ->whereIn('type', [
                        UserCalendar::TYPE_LOCKED,
                        //UserCalendar::TYPE_BOOKED,
                    ])
                    ->whereDate('date_at', $carbonDate);
            })->orDoesntHave('calendarDatesActive');
        });
        return $query;
    }

    /**
     * @param Builder $query
     * @param int $userSaveId
     * @return Builder
     */
    private function queryByUserSave(Builder $query, int $userSaveId)
    {
        /** @var UserSave|null $oSave */
        $oSave = UserSave::find($userSaveId);
        if (is_null($oSave)) {
            return $query;
        }
        $aId = $oSave->listings()->active()->pluck('listings.id')->toArray();
        if (empty($aId)) {
            return $query;
        }
        return $query->whereIn('id', $aId);
    }

    /**
     * @param Builder $query
     * @param int $listingId
     * @return Builder
     */
    private function queryByListingSimilar(Builder $query, int $listingId)
    {
        $oListing = Listing::find($listingId);
        $oListings = (new ListingServiceModel($oListing))->getSimilar();
        $aId = $oListings->pluck('id')->toArray();
        return $query->whereIn('id', $aId);
    }
}
