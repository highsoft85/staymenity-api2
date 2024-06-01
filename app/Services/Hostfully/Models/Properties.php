<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Models;

class Properties
{
    const UID = 'uid';

    // REQUIRED
    const TYPE = 'type';
    const NAME = 'name';
    const AGENCY_UID = 'agencyUid';
    const BASE_GUESTS = 'baseGuests';
    const MAXIMUM_GUESTS = 'maximumGuests';
    const BASE_DAILY_RATE = 'baseDailyRate';
    const CITY = 'city';
    const STATE = 'state';
    const ADDRESS_1 = 'address1';
    const POSTAL_CODE = 'postalCode';
    const COUNTRY_CODE = 'countryCode';

    const WEB_LINK = 'webLink';
    const LATITUDE = 'latitude';
    const LONGITUDE = 'longitude';
    const CLEANING_FEE_AMOUNT = 'cleaningFeeAmount';
    const EXTERNAL_ID = 'externalID';

    const BEDROOMS = 'bedrooms';
    const BATHROOMS = 'bathrooms';
    const PICTURE = 'picture';
    const MINIMUM_STAY = 'minimumStay';
    const MAXIMUM_STAY = 'maximumStay';
    const SECURITY_DEPOSIT_AMOUNT = 'securityDepositAmount';
    const ACCEPT_INSTANT_BOOK = 'acceptInstantBook';
    const IS_ACTIVE = 'isActive';
    const FLOOR = 'floor';
    const AREA_SIZE = 'areaSize';
    const AREA_SIZE_UNIT = 'areaSizeUnit';
    const EXTRA_GUEST_FEE = 'extraGuestFee';
    const TAXATION_RATE = 'taxationRate';
    const PANORAMIC_DATA_URL = 'panoramicDataUrl';
    const CANCELLATION_POLICY = 'cancellationPolicy';
    const GUIDE_BOOK_URL = 'guideBookUrl';
    const BOOKING_WINDOW = 'bookingWindow';
    const BOOKING_WINDOW_AFTER_CHECKOUT = 'bookingWindowAfterCheckout';
    const WEEK_END_RATE_PERCENT_ADJUSTMENT = 'weekEndRatePercentAdjustment';
    const TURN_OVER_DAYS = 'turnOverDays';
    const BOOKING_LEAD_TIME = 'bookingLeadTime';
    const DEFAULT_CHECKIN_TIME = 'defaultCheckinTime';
    const DEFAULT_CHECKOUT_TIME = 'defaultCheckoutTime';
    const WIFI_NETWORK = 'wifiNetwork';
    const WIFI_PASSWORD = 'wifiPassword';
    const ONLY_CHECK_IN_OUT_DAY = 'onlyCheckInOnDay';
    const CURRENCY = 'currency';
    const RENTAL_LICENSE_NUMBER = 'rentalLicenseNumber';
    const RENTAL_LICENSE_NUMBER_EXPIRATION_DATE = 'rentalLicenseNumberExpirationDate';
    const MINIMUM_WEEKEND_STAY = 'minimumWeekendStay';
    const PERCENT_UPON_RESERVATION = 'percentUponReservation';
    const FULL_PAYMENT_TIMING = 'fullPaymentTiming';
}
