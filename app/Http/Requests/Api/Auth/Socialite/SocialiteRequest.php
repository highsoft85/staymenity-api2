<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth\Socialite;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\User;
use Illuminate\Validation\Rule;

class SocialiteRequest extends FormRequestCommon
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
            'access_token' => ['required'],
            'role' => [
                'required',
                'string',
                Rule::in([User::ROLE_HOST, User::ROLE_GUEST]),
            ],
            'user_id' => [
                'integer',
            ],
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
