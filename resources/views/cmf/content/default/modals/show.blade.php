{{--<table class="table table-hover table-bordered table-sm">--}}
{{--    <tbody>--}}
{{--    <tr>--}}
{{--        <td style="border-top: none;">--}}
{{--            <b>ID</b>--}}
{{--        </td>--}}
{{--        <td style="border-top: none;">--}}
{{--            {{ $oItem->id }}--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    @foreach($fields as $field => $value)--}}
{{--        <tr>--}}
{{--            @include('cmf.content.default.table.column.default_table', [--}}
{{--                'name' => $field,--}}
{{--                'item' => $oItem,--}}
{{--                'value' => $value--}}
{{--            ])--}}
{{--        </tr>--}}
{{--    @endforeach--}}
{{--    </tbody>--}}
{{--</table>--}}

@if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.modals.show'))
    @include('cmf.content.' . $model . '.modals.show', [
        'oItem' => $oItem,
    ])
@else
    @foreach($fields as $field => $value)
        <div class="row" style="border-bottom: 1px solid #eee;">
            @include('cmf.content.default.table.column.default_row', [
                'name' => $field,
                'item' => $oItem,
                'value' => $value
            ])
        </div>
    @endforeach
@endif


{{--<div class="form-group">--}}
{{--    <label class="text-muted m-0">ID:</label>--}}
{{--    <br>--}}
{{--    {{ $oItem->id }}--}}
{{--</div>--}}

