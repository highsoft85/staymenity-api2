<?php
/** @var \App\Models\User $oItem */
?>
<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionCustomer', 'id' => $oItem->id]) }}">
    <div class="col-12">
        <div class="form-group">
            <label>Customer</label>
            <input type="text" class="form-control" name="customer_id" placeholder="Customer" value="{{ $oItem->details->customer_id ?? '' }}">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Test customer</label>
            <input type="text" class="form-control" name="test_customer_id" placeholder="Test customer" value="{{ $oItem->details->test_customer_id ?? '' }}">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Payout Account</label>
            <input type="text" class="form-control" name="stripe_account" placeholder="Payout Account" value="{{ $oItem->details->stripe_account ?? '' }}">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Test Payout Account</label>
            <input type="text" class="form-control" name="test_stripe_account" placeholder="Test Payout Account" value="{{ $oItem->details->test_stripe_account ?? '' }}">
        </div>
    </div>
</form>
