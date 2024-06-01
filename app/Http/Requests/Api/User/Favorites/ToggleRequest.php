<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Favorites;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;

class ToggleRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_type' => ['required'],
            'item_id' => ['required'],
            'user_save_id' => [
                'nullable',
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
