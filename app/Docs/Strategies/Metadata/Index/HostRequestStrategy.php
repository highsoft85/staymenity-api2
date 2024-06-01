<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Index;

use App\Docs\Strategy;

class HostRequestStrategy extends Strategy
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
        return $this->route_index_host_request;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'index',
            'groupDescription' => null,
            'title' => 'api/host-request',
            'description' => 'Заявка на хоста',
            'authenticated' => false,
        ];
    }
}
