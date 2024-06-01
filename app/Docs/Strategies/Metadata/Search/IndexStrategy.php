<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Search;

use App\Docs\Strategy;

class IndexStrategy extends Strategy
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
        return $this->route_search;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'search',
            'groupDescription' => null,
            'title' => 'api/search',
            'description' => view('docs.metadata.search.index')->render(),
            'authenticated' => false,
        ];
    }
}
