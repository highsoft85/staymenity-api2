<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Reservations;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Search\SearchService;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_at' => [
                'nullable',
                'string',
                'date_format:Y-m-d'
            ],
            'from_at' => [
                'nullable',
                'string',
                'date_format:Y-m-d'
            ],
            'type' => [
                'nullable',
                'string',
                Rule::in([
                    Reservation::SEARCH_TYPE_UPCOMING,
                    Reservation::SEARCH_TYPE_PREVIOUS,
                    Reservation::SEARCH_TYPE_CANCELLED,
                ]),

            ],
            'limit' => [
                'integer',
            ],
            'page' => [
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
