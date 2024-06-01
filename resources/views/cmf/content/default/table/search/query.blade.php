<div class="form-group float-right">
    <select class="form-control ajax-select" name="{{ $name }}" style="height: 35px;width: 200px;margin-right: 5px;"
            action="{{ routeCmf($model.'.query') }}"
            data-list=".admin-table"
            data-list-action="{{ routeCmf($model.'.view.post') }}"
            data-callback="refreshAfterSubmit"
            data-loading-container=".admin-table"
            data-loading-container=".admin-table"
            data-null-value="{{ isset($nullValue) ? $nullValue : 0 }}"
    >
        <option value="{{ isset($nullValue) ? $nullValue : 0 }}">Все @if(isset($title)){{ mb_strtolower($title) }}@endif</option>
        <?php
        $keyTitle = isset($keyTitle) ? $keyTitle : 'title';
        ?>
        @foreach($values as $key => $oValue)
            @if(isset($oValue->id) && isset($oValue->{$keyTitle}))
                <option value="{{ $oValue->id }}"
                        @if(Session::exists($model) && Session::has($model.'.query') &&
                            isset(Session::get($model.'.query')[$name]) &&
                            intval(Session::get($model.'.query')[$name]) === $oValue->id)
                        selected
                        @endif
                >{{ $oValue->{$keyTitle} }}</option>
            @else
                <option value="{{ $key }}"
                        @if(Session::exists($model) && Session::has($model.'.query') &&
                            isset(Session::get($model.'.query')[$name]) &&
                            intval(Session::get($model.'.query')[$name]) === intval($key))
                        selected
                        @endif
                >{{ isset($oValue['title']) ? $oValue['title'] : $oValue }}</option>
            @endif
        @endforeach
    </select>
</div>
