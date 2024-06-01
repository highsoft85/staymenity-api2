<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\FormRequestCommon;
use App\Models\PersonalVerificationCode;
use Illuminate\Validation\Rule;

class PhoneVerifyRequest extends FormRequestCommon
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => [
                'nullable',
                'string',
                Rule::in([
                    PersonalVerificationCode::TYPE_REGISTRATION,
                    PersonalVerificationCode::TYPE_LOGIN,
                    PersonalVerificationCode::TYPE_RESERVATION,
                    PersonalVerificationCode::TYPE_CHANGE,
                    PersonalVerificationCode::TYPE_RESET,
                    //PersonalVerificationCode::TYPE_VERIFY,
                ]),
            ],
            'user_id' => ['required_without:phone'],

            'phone' => ['required_without:user_id', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'role' => ['string'],
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
     * @return array
     */
    public function bodyParameters()
    {
        return [];
    }
}
