<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Docs\Strategies\BodyParameters\Auth\LoginStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\User;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package App\Http\Requests\Api\Auth
 *
 *
 */
class LoginRequest extends FormRequestCommon
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
     * @bodyParam email string required. Example: admin2@admin.com
     * @bodyParam password string required. Example: password
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'role' => [
                'nullable',
                Rule::in([User::ROLE_HOST, User::ROLE_GUEST]),
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
     * @see LoginStrategy
     * @return array
     */
    public function bodyParameters()
    {
        return [];
    }
}
