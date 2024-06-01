<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Docs\Strategies\BodyParameters\Auth\RegisterStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequestCommon
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
            'role' => ['required', 'string'],

            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],

            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'password' => ['required_with:email', 'string', 'min:8'],

            'phone' => ['sometimes', 'string', 'max:255'],
            'phone_verified' => ['required_with:phone', 'integer'],
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
     * @see RegisterStrategy
     * @return array[]
     */
    public function bodyParameters()
    {
        return [];
    }
}
