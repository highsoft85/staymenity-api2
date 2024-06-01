<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth\Password;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;

class PhoneRequest extends FormRequestCommon
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
            'phone' => ['required', 'string', 'max:255'],
            'phone_verified' => ['required', 'integer'],
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
