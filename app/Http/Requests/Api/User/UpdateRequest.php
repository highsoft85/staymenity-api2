<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Controllers\Api\ApiCommonTrait;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\User;
use App\Services\Search\SearchService;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequestCommon
{
    use ApiCommonTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $oUser = $this->authUser(request());
        return [
            'current_role' => [
                'nullable',
                'string',
                Rule::in([User::ROLE_HOST, User::ROLE_GUEST]),
            ],
            'first_name' => [
                'nullable',
                'string',
            ],
            'last_name' => [
                'nullable',
                'string',
            ],
            'gender' => [
                'nullable',
                'integer',
                Rule::in([User::GENDER_NOT_TO_SAY, User::GENDER_MALE, User::GENDER_FEMALE]),
            ],
            'birthday_at' => [
                'nullable',
                'string',
                'date_format:m/d/Y'
            ],
            'email' => [
                'string',
                'email',
                Rule::unique('users')->ignore($oUser->id)->whereNull('deleted_at'),
            ],
            'hostfully_agency_uid' => [
                'nullable',
                'string',
            ],
            'phone' => [
                'nullable',
                'string',
            ],
            'phone_verified' => [
                'required_with:phone',
                'integer',
            ],
            'description' => [
                'nullable',
                'string',
            ],

            'city' => ['string'],
            'place_id' => ['string'],

            // для смены пароля
            'current_password' => [
                'required_with:new_password', 'string',
            ],
            'new_password' => [
                'required_with:current_password', 'string', 'min:8', 'confirmed',
            ],

            // для создания пароля
            'password' => [
                'nullable', 'string', 'min:8', 'confirmed',
            ],

            'image' => ['nullable'],
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
