<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Index;

use App\Docs\Strategy;

class DataSubjectStrategy extends Strategy
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
        return $this->route_data_subject;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'index',
            'groupDescription' => null,
            'title' => 'api/data/{subject}',
            'description' => view('docs.metadata.index.data_subject', [])->render(),
            'authenticated' => false,
        ];
    }
}
