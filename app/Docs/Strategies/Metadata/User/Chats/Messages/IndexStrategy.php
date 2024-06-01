<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Chats\Messages;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
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
        return $this->route_user_chats_messages_index;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'user/chats/{id}/messages',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.user.chats.messages.index', [])->render(),
            'authenticated' => true,
        ];
    }
}
