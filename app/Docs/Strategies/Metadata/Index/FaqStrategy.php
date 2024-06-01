<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Index;

use App\Docs\Strategy;

class FaqStrategy extends Strategy
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
        return $this->route_faq;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'index',
            'groupDescription' => null,
            'title' => 'api/faq',
            'description' => 'FAQ',
            'authenticated' => false,
        ];
    }
}
