<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Services\Model\UserReservationServiceModel;
use Illuminate\Validation\Rule;

trait ReservationRulesTrait
{
    /**
     * @return array
     */
    protected function commonRules()
    {
        return [
            'listing_id' => ['required'],
            'start_at' => [
                'required',
                'date_format:' . UserReservationServiceModel::DATE_FORMAT,
            ],
            'finish_at' => [
                'required',
                'date_format:' . UserReservationServiceModel::DATE_FORMAT,
            ],
            'guests_size' => ['required'],
            'message' => [
                'nullable',
                'string',
            ],

            // ?
            //'price' => ['required'],
            //'service_fee' => ['required'],
            //'cleaning_fee' => ['required'],
            //'total_price' => ['required'],
        ];
    }

    /**
     * @return array
     */
    protected function rulesForUser()
    {
        return array_merge($this->commonRules(), []);
    }

    /**
     * @return array
     */
    protected function rulesForGuest()
    {
        return array_merge($this->commonRules(), [
            'phone' => ['required', 'string', 'max:255'],
            'phone_verified' => ['required', 'integer'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * @return array
     */
    protected function rulesForUpdate()
    {
        return [
            'card' => ['required'],
            'card.first_name' => ['required'],
            'card.last_name' => ['required'],
            'card.number' => ['required'],
            'card.month' => ['required'],
            'card.year' => ['required'],
            'card.cvc' => ['required'],

            'address' => ['required'],

            'price' => ['required'],
            'service_fee' => ['required'],
            'cleaning_fee' => ['required'],
            'total_price' => ['required'],
        ];
    }
}
