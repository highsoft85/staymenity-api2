@include('cmf.content.default.table.column.delete', [
    'model' => $model,
    'disabled' => \Illuminate\Support\Facades\Auth::user()->id === $oItem->id,
    'deleteKey' => 'user',
    'oItem' => $oItem,
])
