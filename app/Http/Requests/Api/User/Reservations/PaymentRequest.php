<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Reservations;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\User;
use App\Models\UserCalendar;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'payment_card_id' => [
//                //'required_without:token_id',
//                'integer',
//            ],
            'payment_method_id' => [
                //'required_without:token_id',
                'string',
            ],
//            'brand' => [
//                //'required_without:payment_card_id',
//                'string',
//            ],
//            'last' => [
//                //'required_without:payment_card_id',
//                'string',
//            ],
//            'card_id' => [
//                //'required_without:payment_card_id',
//                'string',
//            ],
//            'token_id' => [
//                //'required_without:payment_card_id',
//                'string',
//            ],
            //address_city: null
            //address_country: null
            //address_line1: null
            //address_line1_check: null
            //address_line2: null
            //address_state: null
            //address_zip: "42424"
            //address_zip_check: "unchecked"
            //brand: "Visa"
            //country: "US"
            //cvc_check: "unchecked"
            //dynamic_last4: null
            //exp_month: 4
            //exp_year: 2024
            //funding: "credit"
            //id: "card_1HlfsRIFDQsDl8sw8B7DqD3Q"
            //last4: "4242"
            //name: null
            //object: "card"
            //tokenization_method: null
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
