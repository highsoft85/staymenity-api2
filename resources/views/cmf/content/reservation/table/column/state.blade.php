<?php
/** @var \App\Models\Reservation $oItem */
?>
{{--@if(!is_null($oItem->cancelled_at))--}}
{{--    <a class="btn btn-sm text-danger" role="button"--}}
{{--       data-tippy-popover--}}
{{--       data-tippy-content="Cancelled"--}}
{{--    >--}}
{{--        <i class="fa fa-ban" aria-hidden="true"></i>--}}
{{--    </a>--}}
{{--@endif--}}
{{--@if(!is_null($oItem->accepted_at))--}}
{{--    <a class="btn btn-sm text-success" role="button"--}}
{{--       data-tippy-popover--}}
{{--       data-tippy-content="Accepted"--}}
{{--    >--}}
{{--        <i class="fa fa-check" aria-hidden="true"></i>--}}
{{--    </a>--}}
{{--@endif--}}
{{--@if(!is_null($oItem->declined_at))--}}
{{--    <a class="btn btn-sm text-danger" role="button"--}}
{{--       data-tippy-popover--}}
{{--       data-tippy-content="Declined By Host"--}}
{{--    >--}}
{{--        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>--}}
{{--    </a>--}}
{{--@endif--}}
@if($oItem->fromApp())
    @if(!is_null($oItem->payment))
        <a class="btn btn-sm btn-tiny text-success" role="button"
           data-tippy-popover
           data-tippy-content="Has payment"
        >
            <i class="fa fa-paypal" aria-hidden="true"></i>
        </a>
    @else
        <a class="btn btn-sm btn-tiny text-success grayscale" role="button"
           data-tippy-popover
           data-tippy-content="No payment"
        >
            <i class="fa fa-paypal" aria-hidden="true"></i>
        </a>
    @endif
    @if(!is_null($oItem->transfer))
        <a class="btn btn-sm btn-tiny text-success" role="button"
           data-tippy-popover
           data-tippy-content="Has Transfer"
        >
            <i class="fa fa-exchange" aria-hidden="true"></i>
        </a>
    @else
        <a class="btn btn-sm btn-tiny text-success grayscale" role="button"
           data-tippy-popover
           data-tippy-content="No Transfer"
        >
            <i class="fa fa-exchange" aria-hidden="true"></i>
        </a>
    @endif
    @if(!is_null($oItem->payout))
        <a class="btn btn-sm btn-tiny text-success" role="button"
           data-tippy-popover
           data-tippy-content="Has Payout"
        >
            <i class="fa fa-reply" aria-hidden="true"></i>
        </a>
    @else
        <a class="btn btn-sm btn-tiny text-success grayscale" role="button"
           data-tippy-popover
           data-tippy-content="No Payout"
        >
            <i class="fa fa-reply" aria-hidden="true"></i>
        </a>
    @endif
@endif
@if($oItem->fromHostfully())
    <img src="{{ asset('/img/services/hostfully.png') }}" alt="" width="20"
         data-tippy-popover
         data-tippy-content="Reservation from Hostfully"
    >
@endif
