<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Transformers;

use App\Services\Hostfully\Models\Leads;
use App\Services\Hostfully\Models\LeadsV1;
use Carbon\Carbon;

class LeadTransformer
{
    /**
     * @param array $data
     * @return array
     */
    public function transformFromV1ToV2(array $data)
    {
        return array_merge($this->getDataByStatusFrom($data[LeadsV1::STATUS]), [
            //Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
            //Leads::STATUS => $data[LeadsV1::STATUS],
            Leads::UID => $data[LeadsV1::UID],
            Leads::PROPERTY_UID => $data[LeadsV1::PROPERTY][LeadsV1::PROPERTY_UID],
            Leads::CHILDREN_COUNT => $data[LeadsV1::CHILDREN_COUNT],
            Leads::ADULT_COUNT => $data[LeadsV1::ADULT_COUNT],
            Leads::PET_COUNT => 0,
            Leads::CHECK_IN_DATE => $data[LeadsV1::CHECK_IN_DATE],
            Leads::CHECK_OUT_DATE => $data[LeadsV1::CHECK_OUT_DATE],
            Leads::SOURCE => $data[LeadsV1::SOURCE] ?? 'ORBIRENTAL_FORM',
            Leads::FIRST_NAME => null,
            Leads::LAST_NAME => null,
            Leads::NOTES => $data[LeadsV1::NOTES] ?? '',
            Leads::EMAIL => $data[LeadsV1::EMAIL] ?? 'HIDDEN',
            Leads::PHONE_NUMBER => $data[LeadsV1::EMAIL] ?? 'HIDDEN',
            Leads::EXTERNAL_BOOKING_ID => null,
            Leads::PREFERRED_CURRENCY => null,
            Leads::CITY => $data[LeadsV1::PROPERTY][LeadsV1::PROPERTY_CITY],
            Leads::STATE => $data[LeadsV1::PROPERTY][LeadsV1::PROPERTY_STATE],
            Leads::BOOKED => isset($data[LeadsV1::CREATED])
                ? Carbon::parse($data[LeadsV1::CREATED])->format('Y-m-d H:i')
                : now()->format('Y-m-d H:i'),
            Leads::PREFERRED_LOCALE => null,
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    public function transformFromV2ToV1Update(array $data)
    {
        if (isset($data[Leads::LEAD_TYPE])) {
            unset($data[Leads::LEAD_TYPE]);
        }
        return array_merge($this->getDataByStatusTo($data[Leads::STATUS]), [
            //
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    public function transformFromV2ToV1Create(array $data)
    {
        if (isset($data[Leads::LEAD_TYPE])) {
            unset($data[Leads::LEAD_TYPE]);
        }
        return array_merge($this->getDataByStatusTo($data[Leads::STATUS]), [
            //Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
            //Leads::STATUS => $data[LeadsV1::STATUS],
            'agencyUid' => $data['agencyUid'],
            Leads::PROPERTY_UID => $data[Leads::PROPERTY_UID],
            Leads::CHECK_IN_DATE => $data[Leads::CHECK_IN_DATE],
            Leads::CHECK_OUT_DATE => $data[Leads::CHECK_OUT_DATE],
            Leads::EMAIL => $data[Leads::EMAIL],

            Leads::FIRST_NAME => $data[Leads::FIRST_NAME],
            Leads::LAST_NAME => $data[Leads::LAST_NAME],
            Leads::CHILDREN_COUNT => $data[Leads::CHILDREN_COUNT],
            Leads::ADULT_COUNT => $data[Leads::ADULT_COUNT],
            Leads::PHONE_NUMBER => $data[Leads::PHONE_NUMBER],
            Leads::PET_COUNT => 0,
            Leads::NOTES => $data[Leads::NOTES],
            Leads::CITY => $data[Leads::CITY],
            Leads::STATE => $data[Leads::STATE],
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    public function transformFromV2ToV1CreateBlock(array $data)
    {
        if (isset($data[Leads::LEAD_TYPE])) {
            unset($data[Leads::LEAD_TYPE]);
        }
        return array_merge($this->getDataByStatusTo($data[Leads::STATUS]), [
            //Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
            //Leads::STATUS => $data[LeadsV1::STATUS],
            'agencyUid' => $data['agencyUid'],
            Leads::PROPERTY_UID => $data[Leads::PROPERTY_UID],
            Leads::CHECK_IN_DATE => $data[Leads::CHECK_IN_DATE],
            Leads::CHECK_OUT_DATE => $data[Leads::CHECK_OUT_DATE],
            Leads::EMAIL => $data[Leads::EMAIL],
        ]);
    }

    /**
     * @param string $status
     * @return array
     */
    public function getDataByStatusTo(string $status)
    {
        switch ($status) {
            case Leads::STATUS_INQUIRY_NEW:
                return [
                    Leads::STATUS => LeadsV1::STATUS_NEW,
                ];
            case Leads::STATUS_INQUIRY_ON_HOLD:
                return [
                    Leads::STATUS => LeadsV1::STATUS_ON_HOLD,
                ];
            case Leads::STATUS_BLOCKED:
                return [
                    Leads::STATUS => LeadsV1::STATUS_BLOCKED,
                ];
            case Leads::STATUS_BOOKED:
                return [
                    Leads::STATUS => LeadsV1::STATUS_BOOKED,
                ];
            case Leads::STATUS_BOOKING_BOOKED_BY_AGENT:
                return [
                    Leads::STATUS => LeadsV1::STATUS_BOOKED,
                ];
            case Leads::STATUS_BOOKING_BOOKED_BY_OWNER:
                return [
                    Leads::STATUS => LeadsV1::STATUS_BOOKED,
                ];
            case Leads::STATUS_BOOKING_BOOKED_BY_CUSTOMER:
                return [
                    Leads::STATUS => LeadsV1::STATUS_BOOKED,
                ];
            case Leads::STATUS_BOOKING_CANCELLED:
                return [
                    Leads::STATUS => LeadsV1::STATUS_CANCELLED,
                ];
            case Leads::STATUS_BOOKING_CANCELLED_BY_OWNER:
                return [
                    Leads::STATUS => LeadsV1::STATUS_CANCELLED_BY_OWNER,
                ];
            case Leads::STATUS_BOOKING_CANCELLED_BY_TRAVELER:
                return [
                    Leads::STATUS => LeadsV1::STATUS_CANCELLED_BY_TRAVELER,
                ];
        }
        return [];
    }

    /**
     * @param string $status
     * @return array
     */
    private function getDataByStatusFrom(string $status)
    {
        switch ($status) {
            case LeadsV1::STATUS_NEW:
                return [
                    Leads::LEAD_TYPE => Leads::STATUS_NEW,
                    Leads::STATUS => Leads::STATUS_NEW,
                ];
            case LeadsV1::STATUS_ON_HOLD:
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_INQUIRY,
                    Leads::STATUS => Leads::STATUS_INQUIRY_ON_HOLD,
                ];
            case LeadsV1::STATUS_BLOCKED:
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BLOCK,
                    Leads::STATUS => Leads::STATUS_BLOCK_BLOCKED,
                ];
            case LeadsV1::STATUS_DECLINED:
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
                    Leads::STATUS => Leads::STATUS_BOOKING_CANCELLED_BY_OWNER,
                ];
            case LeadsV1::STATUS_IGNORED:
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
                    Leads::STATUS => Leads::STATUS_BOOKING_CANCELLED,
                ];
            case LeadsV1::STATUS_PAID_IN_FULL:
                //
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
                    Leads::STATUS => Leads::STATUS_BOOKING_BOOKED_BY_AGENT,
                ];
            case LeadsV1::STATUS_BOOKED:
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
                    Leads::STATUS => Leads::STATUS_BOOKING_BOOKED_BY_AGENT,
                ];
            case LeadsV1::STATUS_PENDING:
                // в ожидании
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
                    Leads::STATUS => Leads::STATUS_BOOKING_BOOKED_BY_AGENT,
                ];
            case LeadsV1::STATUS_CANCELLED:
                // просто отменен
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
                    Leads::STATUS => Leads::STATUS_BOOKING_CANCELLED,
                ];
            case LeadsV1::STATUS_CANCELLED_BY_TRAVELER:
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
                    Leads::STATUS => Leads::STATUS_BOOKING_CANCELLED_BY_TRAVELER,
                ];
            case LeadsV1::STATUS_CANCELLED_BY_OWNER:
                // как и отменен как владелец
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
                    Leads::STATUS => Leads::STATUS_BOOKING_CANCELLED_BY_OWNER,
                ];
            case LeadsV1::STATUS_HOLD_EXPIRED:
                // бронь закончена, значит отменить
                return [
                    Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
                    Leads::STATUS => Leads::STATUS_BOOKING_CANCELLED,
                ];
        }
        return [];
    }
}
