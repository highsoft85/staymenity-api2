<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Docs;

use App\Docs\Strategy;

class KeysStrategy extends Strategy
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
        return $this->route_keys;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'About',
            'groupDescription' => 'Ключи для проекта',
            'title' => 'api/keys',
            'description' => view('docs.keys')->render(),
            'authenticated' => false,
        ];
    }
}
