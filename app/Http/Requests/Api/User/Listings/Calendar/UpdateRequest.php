<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Listings\Calendar;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\User;
use App\Models\UserCalendar;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => [
                //'nullable',
                'string',
                'date_format:Y-m-d'
            ],
//            'date_from' => [
//                //'nullable',
//                'string',
//                'date_format:Y-m-d'
//            ],
//            'date_to' => [
//                //'nullable',
//                'string',
//                'date_format:Y-m-d'
//            ],
            'type' => [
                //'nullable',
                'required_with:date',
                'string',
                Rule::in([
                    UserCalendar::TYPE_AVAILABLE,
                    UserCalendar::TYPE_BOOKED,
                    UserCalendar::TYPE_LOCKED,
                ]),
            ],
            'action' => [
                //'nullable',
                'string',
                Rule::in([
                    UserCalendar::ACTION_UNLOCK_ALL,
                    UserCalendar::ACTION_UNLOCK_WEEKENDS,
                    UserCalendar::ACTION_UNLOCK_WEEKDAYS,
                ]),
            ]
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * @see ForgotPasswordStrategy
     * @return array
     */
    public function bodyParameters()
    {
        return [];
    }
}
