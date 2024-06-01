<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Models;

class Leads
{
    const UID = 'uid';
    const LEAD_TYPE = 'leadType';
    const PROPERTY_UID = 'propertyUid';
    const CHILDREN_COUNT = 'childrenCount';
    const ADULT_COUNT = 'adultCount';
    const PET_COUNT = 'petCount';
    const CHECK_IN_DATE = 'checkInDate';
    const CHECK_OUT_DATE = 'checkOutDate';
    const STATUS = 'status';
    const SOURCE = 'source';
    const FIRST_NAME = 'firstName';
    const LAST_NAME = 'lastName';
    const NOTES = 'notes';
    const EMAIL = 'email';
    const PHONE_NUMBER = 'phoneNumber';
    const EXTERNAL_BOOKING_ID = 'externalBookingID';
    const PREFERRED_CURRENCY = 'preferredCurrency';
    const CITY = 'city';
    const STATE = 'state';
    const COUNTRY_CODE = 'countryCode';
    const BOOKED = 'booked';
    const PREFERRED_LOCALE = 'preferredLocale';



    const TYPE_BLOCK = 'BLOCK';
    const TYPE_INQUIRY = 'INQUIRY';
    const TYPE_BOOKING = 'BOOKING';

    const STATUS_NEW = 'NEW';
    const STATUS_BOOKED = 'BOOKED';
    const STATUS_PAID_IN_FULL = 'PAID_IN_FULL';
    const STATUS_BLOCKED = 'BLOCKED';

    const STATUS_BLOCK_BLOCKED = 'BLOCKED';

    const STATUS_INQUIRY_NEW = 'NEW';
    const STATUS_INQUIRY_ON_HOLD = 'ON_HOLD';
    const STATUS_INQUIRY_QUOTE_SENT = 'QUOTE_SENT';
    const STATUS_INQUIRY_HOLD_EXPIRED = 'HOLD_EXPIRED';
    const STATUS_INQUIRY_CLOSED_QUOTE = 'CLOSED_QUOTE';
    const STATUS_INQUIRY_CLOSED_HOLD = 'CLOSED_HOLD';
    const STATUS_INQUIRY_PENDING = 'PENDING';

    const STATUS_BOOKING_BOOKED_BY_AGENT = 'BOOKED_BY_AGENT';
    const STATUS_BOOKING_BOOKED_BY_CUSTOMER = 'BOOKED_BY_CUSTOMER';
    const STATUS_BOOKING_BOOKED_BY_OWNER = 'BOOKED_BY_OWNER';
    const STATUS_BOOKING_BOOKED_EXTERNALLY = 'BOOKED_EXTERNALLY';
    const STATUS_BOOKING_CANCELLED = 'CANCELLED';
    const STATUS_BOOKING_CANCELLED_BY_TRAVELER = 'CANCELLED_BY_TRAVELER';
    const STATUS_BOOKING_CANCELLED_BY_OWNER = 'CANCELLED_BY_OWNER';
    const STATUS_BOOKING_STAY = 'STAY';
    const STATUS_BOOKING_ARCHIVED = 'ARCHIVED';


}
