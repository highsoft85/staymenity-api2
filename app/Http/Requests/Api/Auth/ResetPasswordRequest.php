<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Docs\Strategies\BodyParameters\Auth\ResetPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;

class ResetPasswordRequest extends FormRequestCommon
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
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
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
     * @see ResetPasswordStrategy
     * @return array
     */
    public function bodyParameters()
    {
        return [];
    }
}
