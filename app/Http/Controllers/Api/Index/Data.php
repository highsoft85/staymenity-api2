<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Index;

use App\Http\Transformers\Api\AmenityTransformer;
use App\Http\Transformers\Api\RuleTransformer;
use App\Http\Transformers\Api\TypeTransformer;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\Option;
use App\Models\Reservation;
use App\Models\Rule;
use App\Models\Social;
use App\Models\Type;
use App\Services\Hostfully\Agencies\HostfullyAgenciesService;
use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Model\UserServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Data
{
    const CACHE_CONFIG_KEY = 'data:config';
    const CACHE_TYPES_KEY = 'data:types';
    const CACHE_AMENITIES_KEY = 'data:amenities';
    const CACHE_RULES_KEY = 'data:rules';
    const CACHE_TIMEZONES_KEY = 'data:timezones';

    const CONFIG_RESERVATION_SERVICE_FEE = 'RESERVATION_SERVICE_FEE';
    const CONFIG_RESERVATION_FREE_CANCELLATION = 'RESERVATION_FREE_CANCELLATION';
    const CONFIG_VERIFICATION_LIFETIME = 'VERIFICATION_LIFETIME';
    const CONFIG_RESERVATION_CANCELLATION_CHARGE = 'RESERVATION_CANCELLATION_CHARGE';
    const CONFIG_LOCATION_DEFAULT = 'LOCATION_DEFAULT';
    const CONFIG_TIMEZONE_DEFAULT = 'TIMEZONE_DEFAULT';
    const CONFIG_SOCIALS = 'SOCIALS';
    const CONFIG_IDENTITY_VERIFICATION_TEXTS = 'IDENTITY_VERIFICATION_TEXTS';
    const CONFIG_ENV = 'ENV';
    const CONFIG_URL = 'URL';
    const CONFIG_SEARCH = 'SEARCH';

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $aConfig = remember(self::CACHE_CONFIG_KEY, function () {
            return $this->getConfig();
        });
        $aTypes = remember(self::CACHE_TYPES_KEY, function () {
            return $this->getTypes();
        });
        $aAmenities = remember(self::CACHE_AMENITIES_KEY, function () {
            return $this->getAmenities();
        });
        $aRules = remember(self::CACHE_RULES_KEY, function () {
            return $this->getRules();
        });
//        $aTimezones = remember(self::CACHE_TIMEZONES_KEY, function () {
//            return $this->getTimezones();
//        });
        return responseCommon()->apiDataSuccess([
            'config' => $aConfig,
            'types' => $aTypes,
            'amenities' => $aAmenities,
            'rules' => $aRules,
            //'timezones' => $aTimezones,
        ]);
    }

    /**
     * Обновить то что в invoke
     *
     * @param Request $request
     * @return array
     */
    public function dataUpdate(Request $request)
    {
        $this->__invoke($request);
        return responseCommon()->apiSuccess([]);
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        return [
            self::CONFIG_ENV => config('app.env'),
            self::CONFIG_RESERVATION_SERVICE_FEE => Reservation::SERVICE_FEE,
            self::CONFIG_RESERVATION_FREE_CANCELLATION => Reservation::FREE_CANCELLATION,
            self::CONFIG_VERIFICATION_LIFETIME => config('verification.lifetime'),
            self::CONFIG_RESERVATION_CANCELLATION_CHARGE => Reservation::CANCELLATION_CHARGE,
            self::CONFIG_LOCATION_DEFAULT => (new UserServiceModel())->defaultCoordinates(),
            self::CONFIG_TIMEZONE_DEFAULT => config('app.timezone'),
            self::CONFIG_SOCIALS => $this->getSocials(),
            self::CONFIG_IDENTITY_VERIFICATION_TEXTS => $this->getIdentityTexts(),
            self::CONFIG_URL => [
                'terms' => route('web.terms'),
            ],
            self::CONFIG_SEARCH => [
                'max_price' => 1000,
            ],
        ];
    }

    /**
     * @return array
     */
    private function getTypes()
    {
        return Type::active()->ordered()->get()->transform(function (Type $item) {
            return (new TypeTransformer())->transform($item);
        })->toArray();
    }

    /**
     * @return array
     */
    private function getAmenities()
    {
        return Amenity::active()->ordered()->get()->transform(function (Amenity $item) {
            return (new AmenityTransformer())->transform($item);
        })->toArray();
    }

    /**
     * @return array
     */
    private function getRules()
    {
        return Rule::active()->ordered()->get()->transform(function (Rule $item) {
            return (new RuleTransformer())->transform($item);
        })->toArray();
    }

    /**
     * @return array
     */
    private function getTimezones()
    {
        $timezone = [];
        $timestamp = time();
        foreach (timezone_identifiers_list(\DateTimeZone::ALL) as $key => $t) {
            date_default_timezone_set($t);
            $timezone[$key]['key'] = $t;
            $timezone[$key]['value'] = '(' . date('P', $timestamp) . ') ' . $t;
            $timezone[$key]['difference'] = date('P', $timestamp);
        }
        $timezone = collect($timezone)->sortBy('difference')->values()->toArray();
        return $timezone;
    }

    /**
     * @return array
     */
    private function getSocials()
    {
        return [
            Social::TYPE_FACEBOOK => $this->socialOptionByName(Option::NAME_SOCIAL_FACEBOOK),
            Social::TYPE_TWITTER => $this->socialOptionByName(Option::NAME_SOCIAL_TWITTER),
            Social::TYPE_INSTAGRAM => $this->socialOptionByName(Option::NAME_SOCIAL_INSTAGRAM),
        ];
    }

    /**
     * @return array
     */
    private function getIdentityTexts()
    {
        return [
            'notification' => [
                'description' => 'Verify your ID for increase trust from your guests.',
            ],
            'about' => [
                'title' => 'Why it is important',
                'description' => 'Identity verification helps us to provide security for both – hosts and guests. We never collect or store your personal information.',
            ],
            'type' => [
                'title' => 'Select ID type',
                'description' => 'Please select an ID type you are going to verify',
            ],
            'front_drivers' => [
                'title' => 'Upload a photo of the front of your ID',
                'description' => 'Place on a plain surface - avoid holding it in your hand. Take in well-lit room and avoide glare. Make sure nothing is cut off.',
            ],
            'front_passport' => [
                'title' => 'Upload a photo of the main page of your passport',
                'description' => 'Place on a plain surface - avoid holding it in your hand. Take in well-lit room and avoide glare. Make sure the entire document is in frame.',
            ],
            'back_drivers' => [
                'title' => 'Take a photo of the back of your ID',
                'description' => 'Ensure BAR code is visible and nothing is cut off.',
                //'description' => 'Place on a plain surface - avoid holding it in your hand. Ensure barcode is visible. Make sure the entire document is in frame.',
            ],
            'selfie' => [
                'title' => 'Take a selfie',
                'description' => 'Face must be clearly visible. Make sure there is good lightning. Remove hats, masks or any items covering your face.',
            ],
            'review' => [
                'title' => 'Review the photo',
                'description' => 'Make sure that the photo is clear and nothing is cut off and no glare.',
            ],
            'success' => [
                'title' => 'Your ID in under review',
                'description' => 'Thank you! We will be back shortly. You can continue using Staymenity as usual.',
            ]
        ];
    }

    /**
     * @param string $name
     * @return string|null
     */
    private function socialOptionByName(string $name)
    {
        /** @var Option|null $oOption */
        $oOption = Option::where('name', $name)->first();
        if (is_null($oOption)) {
            return null;
        }
        $oSystemOption = $oOption->systemValue;
        if (is_null($oSystemOption)) {
            return null;
        }
        return $oSystemOption->value;
    }
}
