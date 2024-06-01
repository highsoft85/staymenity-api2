<?php
/** @var \App\Models\User $oItem */
?>

@if(!is_null($oItem->socialAccounts->where('provider', \App\Services\Socialite\FacebookAccountService::NAME)->first()))
    <a class="btn btn-sm btn-tiny" role="button"
       data-tippy-popover
       data-tippy-content="Auth by Facebook"
       style="color: #4267B2;width: 18px;padding: 0;"
    >
        <i class="fa fa-facebook" aria-hidden="true"></i>
    </a>
@endif
@if(!is_null($oItem->socialAccounts->where('provider', \App\Services\Socialite\GoogleAccountService::NAME)->first()))
    <a class="btn btn-sm btn-tiny text-success" role="button"
       data-tippy-popover
       data-tippy-content="Auth by Google"
       style="color: #0F9D58;"
    >
        <i class="fa fa-google" aria-hidden="true"></i>
    </a>
@endif
@if(!is_null($oItem->socialAccounts->where('provider', \App\Services\Socialite\AppleAccountService::NAME)->first()))
    <a class="btn btn-sm btn-tiny text-default" role="button"
       data-tippy-popover
       data-tippy-content="Auth by Apple"
       style="color: #ccc;"
    >
        <i class="fa fa-apple" aria-hidden="true"></i>
    </a>
@endif
