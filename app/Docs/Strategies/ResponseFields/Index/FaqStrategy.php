<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\Index;

use App\Http\Controllers\Api\Index\Data;
use App\Docs\Strategy;
use Illuminate\Http\Request;

class FaqStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_faq;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return [
            'title' => [
                'type' => 'string',
                'description' => 'Вопрос',
            ],
            'description' => [
                'type' => 'string',
                'description' => 'Ответ',
            ],
        ];
    }
}
