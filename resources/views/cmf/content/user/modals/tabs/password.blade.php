<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['name' => 'actionChangePassword', 'id' => $oItem->id]) }}">
    <div class="col-12">
        <div class="form-group">
            <label>New password</label>
            <input type="password" class="form-control" name="password" placeholder="New password" value="">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Confirm password</label>
            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" value="">
        </div>
    </div>
</form>
