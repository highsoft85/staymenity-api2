<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\Index;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;

class DataSubjectStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_URL_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_data_subject;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'subject' => [
                'type' => 'string',
                'description' => 'Название субьекта',
                'required' => true,
                'value' => 'privacy',
            ],
        ];
    }
}
