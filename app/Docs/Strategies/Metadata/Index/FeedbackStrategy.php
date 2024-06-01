<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Index;

use App\Docs\Strategy;

class FeedbackStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_METADATA;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_feedback;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'index',
            'groupDescription' => null,
            'title' => 'api/feedback',
            'description' => 'Форма обратной связи',
            'authenticated' => false,
        ];
    }
}
