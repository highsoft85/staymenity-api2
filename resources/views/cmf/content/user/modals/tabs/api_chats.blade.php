<?php
/** @var \App\Models\User $oItem */
?>
<div class="hr-label">
    <label>chats</label>
    <hr>
</div>
<div>
    {{ ddWithoutExit(cmfToInvoke($oItem, App\Http\Controllers\Api\User\Chats\Index::class, null, [])['data']) }}
</div>

@foreach($oItem->chatsActive as $oChat)
    <div class="hr-label">
        <label>chat messages. Chat: {{ $oChat->id }}</label>
        <hr>
    </div>
    <div>
        {{ ddWithoutExit(cmfToInvoke($oItem, App\Http\Controllers\Api\User\Chats\Messages\Index::class, App\Http\Requests\Api\User\Chats\Messages\IndexRequest::class, [], $oChat->id)['data']) }}
    </div>
@endforeach
