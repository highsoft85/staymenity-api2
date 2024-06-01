<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Docs;

use App\Docs\Strategy;

class DocsStrategy extends Strategy
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
        return $this->route_docs;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'About',
            'groupDescription' => 'Содержится описание проекта',
            'title' => 'api/docs',
            'description' => view('docs.about')->render(),
            'authenticated' => false,
        ];
    }
}
