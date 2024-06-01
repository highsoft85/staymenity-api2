<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Console\Commands\Sync\SyncReservationsCommand;
use App\Exceptions\ResourceExceptionValidation;
use App\Mail\Listing\NewListingMail;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\ListingTime;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\Rule;
use App\Models\Type;
use App\Models\UserCalendar;
use App\Services\Geocoder\GeocoderCitiesService;
use App\Services\Geocoder\GeocoderTimezoneService;
use App\Services\Image\ImageType;
use App\Services\Image\Upload\ImageUploadModelService;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;

class ListingServiceModel extends BaseServiceModel
{
    const STATUS_UNLIST = 'unlist';
    const STATUS_PUBLISH = 'publish';

    /**
     * @var Listing
     */
    private $oListing;

    /**
     * ListingServiceModel constructor.
     * @param Listing $oListing
     */
    public function __construct(Listing $oListing)
    {
        $this->oListing = $oListing;
    }

    /**
     * @param array $data
     * @return Listing
     */
    public function update(array $data)
    {
        if (is_null($this->oListing->price) && isset($data['price'])) {
            $this->setStatusPublish();
        }
        $save = $this->getSaveData($data);
        $this->oListing->update($save);

        // последний шаг
        if (isset($data['price'])) {
            // пусть публикация будет только через доп меню
            //$this->setStatusPublish();
        }
        if (isset($data['rules'])) {
            $this->saveRules($data);
        }
        if (isset($data['amenities'])) {
            $this->saveAmenities($data);
        }
        if (isset($data['type_id'])) {
            $this->saveType($data);
        }
        if (isset($data['address_two'])) {
            $this->saveAddressTwo($data);
        }
        if (isset($data['place_id'])) {
            $oLocation = $this->oListing->location;
            if (!is_null($oLocation) && $oLocation->place_id === $data['place_id']) {
                // если один и тот же
            } else {
                $this->saveLocation($data['place_id']);
            }
        }
        if (isset($data['status']) && !is_null($data['status'])) {
            $this->setStatus($data['status']);
        }

        if (array_key_exists('hostfully_property_uid', $data)) {
            $this->saveHostfully($data['hostfully_property_uid']);
        }
        return $this->oListing;
    }

    /**
     * @param string|null $propertyUid
     */
    private function saveHostfully(?string $propertyUid)
    {
        $oModel = $this->oListing->hostfully;

        if (empty($propertyUid) && !is_null($oModel)) {
            $oModel->delete();
        } else {
            if (!is_null($oModel)) {
                $oModel->update([
                    'uid' => $propertyUid,
                ]);
            } else {
                if (is_null($propertyUid)) {
                    return;
                }
                $this->oListing->hostfully()->create([
                    'uid' => $propertyUid,
                ]);
            }
        }
    }

    /**
     *
     */
    public function newListingMail()
    {
        if (config('staymenity.new_listing_enabled')) {
            $emails = explode(',', config('staymenity.new_listing_recipients'));
            foreach ($emails as $recipient) {
                Mail::to($recipient)->send(new NewListingMail($this->oListing));
            }
        }
    }

    /**
     * @param string $name
     */
    private function setStatus(string $name)
    {
        switch ($name) {
            case self::STATUS_UNLIST:
                $this->setStatusUnlist();
                break;
            case self::STATUS_PUBLISH:
                $this->setStatusPublish();
                break;
        }
    }


    /**
     * @param array $data
     * @return array
     */
    private function getSaveData(array $data)
    {
        $return = [];
        if (isset($data['price'])) {
            $return = array_merge($return, [
                'price' => $data['price'],
                'deposit' => $data['deposit'] ?? null,
                'cleaning_fee' => $data['cleaning_fee'] ?? null,
            ]);
        }
        if (isset($data['description'])) {
            $return = array_merge($return, [
                'description' => $data['description'],
            ]);
        }
        if (isset($data['title'])) {
            $return = array_merge($return, [
                'title' => $data['title'],
            ]);
        }
        if (isset($data['guests_size'])) {
            $return = array_merge($return, [
                'guests_size' => $data['guests_size'],
            ]);
        }
        if (isset($data['rent_time_min'])) {
            $return = array_merge($return, [
                'rent_time_min' => $data['rent_time_min'],
            ]);
        }
        return $return;
    }

    /**
     * @param string $place_id
     */
    public function saveLocation(string $place_id)
    {
        $oLocation = $this->baseSaveLocation($this->oListing, $place_id);
        if (!is_null($oLocation)) {
            $this->updateTimezone();
        }
    }

    /**
     * @param array $data
     */
    public function saveRules(array $data)
    {
        $oRules = Rule::whereIn('id', $data['rules'])->get();
        $this->oListing->rules()->sync($oRules);
        $oSettings = $this->oListing->settings;
        $hasOther = $oRules->where('name', Rule::NAME_OTHER)->first();
        if (!empty($data['rules_other']) && !is_null($hasOther)) {
            $oSettings->update([
                'rules' => $data['rules_other'],
            ]);
        } else {
            $oSettings->update([
                'rules' => null,
            ]);
        }
    }

    /**
     * @param array $data
     */
    public function saveAmenities(array $data)
    {
        $oAmenities = Amenity::whereIn('id', $data['amenities'])->get();
        $this->oListing->amenities()->sync($oAmenities);
        $hasOther = $oAmenities->where('name', Amenity::NAME_OTHER)->first();
        $oSettings = $this->oListing->settings;
        if (!empty($data['amenities_other']) && !is_null($hasOther)) {
            $oSettings->update([
                'amenities' => $data['amenities_other'],
            ]);
        } else {
            $oSettings->update([
                'amenities' => null,
            ]);
        }
    }

    /**
     * @param array $data
     */
    public function saveType(array $data)
    {
        /** @var Type $oType */
        $oType = Type::find($data['type_id']);
        $this->oListing->update([
            'type_id' => $oType->id,
        ]);
        $oSettings = $this->oListing->settings;
        if (!empty($data['type_other']) && $oType->name === Type::NAME_OTHER) {
            $oSettings->update([
                'type' => $data['type_other'],
            ]);
        } else {
            $oSettings->update([
                'type' => null,
            ]);
        }
    }

    /**
     * @param array $times
     */
    public function saveTimes(array $times)
    {
        $this->checkTime($times);
        $this->oListing->times()->delete();
        if (isset($times[ListingTime::TYPE_WEEKDAYS])) {
            $this->saveTimeByType($times[ListingTime::TYPE_WEEKDAYS], ListingTime::TYPE_WEEKDAYS);
        }
        if (isset($times[ListingTime::TYPE_WEEKENDS])) {
            $this->saveTimeByType($times[ListingTime::TYPE_WEEKENDS], ListingTime::TYPE_WEEKENDS);
        }
    }

    /**
     * @param array $times
     */
    private function checkTime(array $times)
    {
        // проверка что все есть
        $rules = ['required'];
        $validation = [];
        foreach ($times as $type => $data) {
            foreach ($data as $key => $value) {
                $validation[$type . '.' . $key . '.from'] = $rules;
                $validation[$type . '.' . $key . '.to'] = $rules;
            }
        }
        $validator = Validator::make($times, $validation);
        if ($validator->fails()) {
            $messages = responseCommon()->validationGetMessages($validator);
            $first = array_shift($messages);
            throw new ResourceExceptionValidation($first);
        }

        // проверка по значениям
        $integerTimes = [];
        foreach ($times as $type => $data) {
            foreach ($data as $key => $value) {
                $from = (int)date('H', strtotime($value['from']));
                $to = (int)date('H', strtotime($value['to']));
                if ($to === 0) {
                    $to = 24;
                }
                if ($to === 1) {
                    $to = 25;
                }
                $integerTimes[$type][$key] = [
                    'from' => $from,
                    'to' => $to,
                ];
            }
        }

        $minSlot = 0;
        $maxSlot = 24;

        $validation = [];
        $messages = [];
        $timestampTimes = [];
        foreach ($integerTimes as $type => $data) {
            foreach ($data as $key => $value) {
                $timestampTimes[$type][$key]['from'] = $value['from'];
                $timestampTimes[$type][$key]['to'] = $value['to'];

                // min 9 max 23
                $max = $maxSlot - 1;
                $rulesFrom = ['integer', 'min:' . $minSlot, 'max:' . $max];

                // min {min} max 24
                $min = $value['from'] + 1;
                $rulesTo = ['integer', 'min:' . $min, 'max:' . $maxSlot];

                // правила
                $validation[$type . '.' . $key . '.from'] = $rulesFrom;
                $validation[$type . '.' . $key . '.to'] = $rulesTo;

                $slotNumber = $key + 1;

                $text = 'Time slot ' . $slotNumber;

                $textForFinish = Str::upper(date('h:i a', strtotime($max . ':00:00')));
                $messages[$type . '.' . $key . '.from.min'] = $text . ' From must be starting at 00:00 AM';
                $messages[$type . '.' . $key . '.from.max'] = $text . ' From must be ending at equals or before ' . $textForFinish;

                $textForStart = Str::upper(date('h:i a', strtotime($min . ':00:00')));
                $messages[$type . '.' . $key . '.to.min'] = $text . ' To must be starting at ' . $textForStart;
                $messages[$type . '.' . $key . '.to.max'] = $text . ' To must be ending at equals or before 00:00 AM';
            }
        }
        $validator = Validator::make($timestampTimes, $validation, $messages);
        if ($validator->fails()) {
            $messages = responseCommon()->validationGetMessages($validator);
            $first = array_shift($messages);
            throw new ResourceExceptionValidation($first);
        }
    }

    /**
     * @param array $times
     * @param string $type
     */
    private function saveTimeByType(array $times, string $type)
    {
        foreach ($times as $data) {
            $this->oListing->times()->create([
                'type' => $type,
                'from' => $this->getTimeFromValue($data['from']),
                'to' => $this->getTimeFromValue($data['to']),
            ]);
        }
    }

    /**
     * @param string|null $value
     * @return string|null
     */
    private function getTimeFromValue(?string $value = null)
    {
        if (is_null($value)) {
            return $value;
        }
        $timestamp = strtotime($value);
        return date("H:i:s", $timestamp);
    }

    /**
     * @return array
     */
    public function getStatus()
    {
        if ($this->oListing->isDraft()) {
            return $this->getCurrentStatusDataByStatusName(Listing::STATUS_NAME_DRAFT);
        }
//        if (is_null($this->oListing->published_at)) {
//            return $this->getCurrentStatusDataByStatusName(Listing::STATUS_NAME_UNLISTED);
//        }
        if ($this->oListing->isBanned()) {
            return $this->getCurrentStatusDataByStatusName(Listing::STATUS_NAME_BANNED);
        }
        if ($this->isBooked()) {
            return $this->getCurrentStatusDataByStatusName(Listing::STATUS_NAME_BOOKED);
        }
        if ($this->isUnavailable()) {
            return $this->getCurrentStatusDataByStatusName(Listing::STATUS_NAME_UNAVAILABLE);
        }
        // @todo on review
        // @todo on pending
        return $this->getCurrentStatusDataByStatusName(Listing::STATUS_NAME_FREE);
    }

    /**
     * @param Carbon|null $date
     * @return bool
     */
    private function isBooked(?Carbon $date = null)
    {
        if (is_null($date)) {
            $date = now()->copy()->timezone($this->oListing->timezone);
        }
        $bookedDate = $this->oListing
            ->reservationsActive()
            ->beginning()
            ->first();
        return !is_null($bookedDate);
    }

    /**
     * @param Carbon|null $date
     * @return bool
     */
    private function isUnavailable(?Carbon $date = null)
    {
        if (is_null($date)) {
            $date = now()->copy()->timezone($this->oListing->timezone);
        }
        $bookedDate = $this->oListing
            ->calendarDatesActive()
            ->whereIn('type', [
                UserCalendar::TYPE_LOCKED,
            ])
            ->whereDate('date_at', $date->format('Y-m-d'))
            ->first();
        return !is_null($bookedDate);
    }

    /**
     * @param string $status
     * @return array
     */
    protected function getCurrentStatusDataByStatusName(string $status)
    {
        return [
            'name' => $status,
            'title' => $this->oListing->currentStatuses()[$status],
        ];
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        $result = transaction()->commitAction(function () {
            $oListing = $this->oListing;
            // удаление локаций
            $oListing->locations()->delete();
            $oListing->reviews()->delete();
            //$oListing->reservations()->delete();
            $oReservations = $oListing->reservations;
            foreach ($oReservations as $oReservation) {
                // если прошла, то ничего не делаем
                if ($oReservation->isPassed()) {
                    continue;
                }
                // если идет, то ничего не делаем
                if ($oReservation->isBeginning()) {
                    continue;
                }
                if ($oReservation->isCancelled() || $oReservation->isDeclined()) {
                    continue;
                }
                // иначе отклоняем
                (new ReservationServiceModel($oReservation))
                    ->setCancelledType(Reservation::CANCELLED_TYPE_BY_LISTING_DELETED)
                    ->setDeclined();
            }
            $oListing->visits()->delete();
            $oListing->times()->delete();
            $oListing->settings()->delete();
            //$oListing->rules()->delete();
            //$oListing->amenities()->delete();
            // собрать изображения
            $oImages = $oListing->modelImages;
            $oListing->update([
                'status' => Listing::STATUS_NOT_ACTIVE,
            ]);
            slackInfo($oListing->id, 'Listing DELETE');
            $oListing->delete();
            // удаление изображений после успешных удалений
            $type = ImageType::MODEL;
            $options = (new \App\Cmf\Project\Listing\ListingController())->image[$type];
            foreach ($oImages as $oImage) {
                (new ImageUploadModelService())->delete($oListing, $oImage, $options);
            }
        });
        return $result->isSuccess();
    }

    /**
     *
     */
    public function setStatusUnlist()
    {
        $this->oListing->update([
            'status' => Listing::STATUS_NOT_ACTIVE,
            'published_at' => null,
        ]);
    }

    /**
     *
     */
    public function setStatusPublish()
    {
        $this->oListing->update([
            'status' => Listing::STATUS_ACTIVE,
            'published_at' => now(),
        ]);
    }

    /**
     * @param int|null $take
     * @return Listing[]|Collection
     */
    public function getSimilar(?int $take = null)
    {
        $typeId = $this->oListing->type_id;
        $point = $this->oListing->location->pointArray;

        $query = Listing::active()
            ->where('id', '<>', $this->oListing->id)
            ->where('type_id', $typeId)
            ->whereHas('user', function (Builder $q) {
                $q->active();
                $q->where('has_payout_connect', 1);
            })
            ->locationInDistance($point, 50);

        $query->orderedBySearch();

        if (is_null($take)) {
            $query->take(8);
        } else {
            $query->take($take);
        }
        return $query->get();
    }

    /**
     *
     */
    public function updateRating()
    {
        $this->oListing->update([
            'run_rating' => $this->oListing->ratingsToAverageByReview(),
        ]);
    }

    /**
     *
     */
    public function updateTimezone()
    {
        $oLocation = $this->oListing->location()->first();
        if (!is_null($oLocation)) {
            $timezone = (new GeocoderCitiesService())->timezoneByPlace($oLocation->place_id);
            $this->oListing->update([
                'timezone' => $timezone,
            ]);
        }
    }

    /**
     * @param array $data
     */
    private function saveAddressTwo(array $data)
    {
        $oSettings = $this->oListing->settings;
        if (isset($data['address_two'])) {
            $oSettings->update([
                'address_two' => $data['address_two'],
            ]);
        }
    }

    /**
     * @return bool
     */
    public function hasFutureReservations()
    {
        $oReservations = $this->oListing
            ->reservations()
            ->futureNotPassed()
            ->where('listing_id', $this->oListing->id)
            ->get();
        if ($oReservations->count() !== 0) {
            return true;
        }
        return false;
    }
}
