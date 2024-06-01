<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Reservations\Review;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Search\SearchService;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => [
                'nullable',
                'string',
            ],
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
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
