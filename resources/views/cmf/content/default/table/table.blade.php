<?php
$model = $model ?? $model;
?>
<table class="table table-admin table--mobile {{ !empty($indexComponent['edit']) && $indexComponent['edit'] && !empty($indexComponent['delete']) && $indexComponent['delete'] ? '__with-edit-delete' : ''}} table-hover __table-is-{{ $model }}">
    <thead>
    <tr>
        <th class="is-id">#</th>
        @if(!empty($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_IMAGE]) && $indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_IMAGE])
            <th class="is-image"></th>
        @endif
        @if(!empty($indexComponent['release_at']) && $indexComponent['release_at'])
            <th class="is-release_at"><i class="icon-feed" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.release_at') }}"></i></th>
        @endif
        @if(!empty($indexComponent['private_show']) && $indexComponent['private_show'])
            <th class="is-private_show"><i class="icon-link" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.private_show') }}"></i></th>
        @endif
        @foreach($fields as $name => $field)
            @if (!empty($field['in_table']))
                <th class="is-{{ $name }}">{!! $field['table_title'] ?? $field['title'] !!}</th>
                @if (!empty($field['delete_title']))
                    @php
                        $delete_title = $field['delete_title'];
                        $delete_title_name = $name;
                    @endphp
                @endif
            @endif
        @endforeach
        @if(!empty($indexComponent['comments']))
            <th class="is-comments"><i class="icon-speech" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.comments') }}"></i></th>
        @endif
        @if(!empty($indexComponent['state']))
            <th class="is-state"><i class="icon-options" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.state') }}"></i></th>
        @endif
        @if(!empty($indexComponent['user_actions']) && member()->isSuperAdmin())
            <th class="is-user_actions"><i class="icon-people" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.user_actions') }}"></i></th>
        @endif
        @if(!empty($indexComponent['show']) && $indexComponent['show'])
            <th class="is-view"><i class="icon-eye" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.show') }}"></i></th>
        @endif
        @if(!empty($indexComponent['edit']) && $indexComponent['edit'])
            <th><i class="icon-pencil" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.edit') }}"></i></th>
        @endif
        @if(!empty($indexComponent['delete']) && $indexComponent['delete'])
            <th><i class="icon-trash icons" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.delete') }}"></i></th>
        @endif
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>#</th>
        @if(!empty($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_IMAGE]) && $indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_IMAGE])
            <th class="is-image"></th>
        @endif
        @if(!empty($indexComponent['release_at']) && $indexComponent['release_at'])
            <th class="is-release_at"><i class="icon-feed" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.release_at') }}"></i></th>
        @endif
        @if(!empty($indexComponent['private_show']) && $indexComponent['private_show'])
            <th class="is-private_show"><i class="icon-link" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.private_show') }}"></i></th>
        @endif
        @foreach($fields as $name => $field)
            @if(!empty($field['in_table']))
                <th class="is-{{ $name }}">{!! $field['table_title'] ?? $field['title'] !!}</th>
            @endif
        @endforeach
        @if(!empty($indexComponent['comments']))
            <th class="is-comments"><i class="icon-speech" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.comments') }}"></i></th>
        @endif
        @if(!empty($indexComponent['state']))
            <th class="is-state"><i class="icon-options" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.state') }}"></i></th>
        @endif
        @if(!empty($indexComponent['user_actions']) && member()->isSuperAdmin())
            <th class="is-user_actions"><i class="icon-people" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.user_actions') }}"></i></th>
        @endif
        @if(!empty($indexComponent['show']) && $indexComponent['show'])
            <th class="is-view"><i class="icon-eye" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.show') }}"></i></th>
        @endif
        @if(!empty($indexComponent['edit']) && $indexComponent['edit'])
            <th><i class="icon-pencil" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.edit') }}"></i></th>
        @endif
        @if(!empty($indexComponent['delete']) && $indexComponent['delete'])
            <th><i class="icon-trash icons" data-tippy-popover data-tippy-content="{{ __('cmf/table.column.delete') }}"></i></th>
        @endif
    </tr>
    </tfoot>
    <tbody>
    @foreach($oItems as $oItem)
        <tr @if(!(method_exists($oItem, 'isActive') ? $oItem->isActive() : $oItem->active)) class="text-opacity" @endif>
            <td>{{ $oItem->id }}</td>
            @if(!empty($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_IMAGE]) && $indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_IMAGE])
                <td>
                    @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.table.column.image'))
                        @include('cmf.content.' . $model . '.table.column.image', [
                            'oItem' => $oItem,
                        ])
                    @else
                        @include('cmf.content.default.table.column.image', [
                            'oItem' => $oItem,
                            'path' => 'square'
                        ])
                    @endif
                </td>
            @endif
            @if(!empty($indexComponent['release_at']) && $indexComponent['release_at'])
                <td class="is-release_at">
                    @include('cmf.content.default.table.column.release_at', [
                        'oItem' => $oItem,
                    ])
                </td>
            @endif
            @if(!empty($indexComponent['private_show']) && $indexComponent['private_show'])
                <td class="is-private_show">
                    @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.table.column.private_show'))
                        @include('cmf.content.' . $model . '.table.column.private_show', [
                            'oItem' => $oItem,
                        ])
                    @else
                        @include('cmf.content.default.table.column.private_show', [
                            'oItem' => $oItem,
                        ])
                    @endif
                </td>
            @endif
            @foreach($fields as $name => $field)
                @if (!empty($field['in_table']))
                    <td class="is-{{ $name }}">
{{--                        @if($loop->first)--}}
{{--                            @include('cmf.content.default.table.column.show', [--}}
{{--                                'oItem' => $oItem,--}}
{{--                                'init'  => ''--}}
{{--                            ])--}}
{{--                        @endif--}}

                        @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.table.column.' . $name))
                            @include('cmf.content.' . $model . '.table.column.' . $name, [
                                'name' => $name,
                                'item' => $oItem,
                                'value' => $field,
                                'hide_title' => true,
                            ])
                        @else
                            @include('cmf.content.default.table.column.default', [
                                'name' => $name,
                                'item' => $oItem,
                                'value' => $field,
                                'hide_title' => true,
                                'indexComponentTitleBordered' => !empty($indexComponent['title_bordered']) && $indexComponent['title_bordered'],
                            ])
                        @endif

                    </td>
                @endif
            @endforeach
            @if(!empty($indexComponent['comments']))
                <td>
                    @include('cmf.content.default.table.column.comments', [
                        'oItem' => $oItem,
                        'isRole' => 'edit-comments',
                    ])
                </td>
            @endif
            @if(!empty($indexComponent['state']))
                <td class="is-state">
                    @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.table.column.state'))
                        @include('cmf.content.' . $model . '.table.column.state', [
                            'model' => $model,
                            'oItem' => $oItem,
                        ])
                    @else
                        @include('cmf.content.default.table.column.state', [
                            'model' => $model,
                            'oItem' => $oItem,
                        ])
                    @endif
                </td>
            @endif
            @if(!empty($indexComponent['user_actions']) && member()->isSuperAdmin())
                <td class="is-user_actions">
                    @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.table.column.user_actions'))
                        @include('cmf.content.' . $model . '.table.column.user_actions', [
                            'model' => $model,
                            'oItem' => $oItem,
                        ])
                    @else
                        @include('cmf.content.default.table.column.user_actions', [
                            'model' => $model,
                            'oItem' => $oItem,
                        ])
                    @endif
                </td>
            @endif
            @if(!empty($indexComponent['show']) && $indexComponent['show'])
                <td class="is-view">
                    @include('cmf.content.default.table.column.view', [
                        'oItem' => $oItem,
                    ])
                </td>
            @endif
            @if(!empty($indexComponent['edit']) && $indexComponent['edit'])
                <td>
                    @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.table.column.edit'))
                        @include('cmf.content.' . $model . '.table.column.edit', [
                            'oItem' => $oItem,
                        ])
                    @else
                        @include('cmf.content.default.table.column.edit', [
                            'oItem' => $oItem,
                            'init' => 'uploader',
                            'disabled' => isset($indexComponent['edit_disabled']) && $indexComponent['edit_disabled'],
                        ])
                    @endif
                </td>
            @endif
            @if(!empty($indexComponent['delete']) && $indexComponent['delete'])
                <td>
                    @if(\Illuminate\Support\Facades\View::exists('cmf.content.' . $model . '.table.column.delete'))
                        @include('cmf.content.' . $model . '.table.column.delete', [
                            'model' => $model,
                            'oItem' => $oItem,
                            'deleteKey' => $delete_title ?? $model,
                            'deleteValue' => isset($delete_title_name) ? $oItem->$delete_title_name : $model,
                        ])
                    @else
                        @include('cmf.content.default.table.column.delete', [
                            'model' => $model,
                            'oItem' => $oItem,
                            'deleteKey' => $delete_title ?? $model,
                            'deleteValue' => isset($delete_title_name) ? $oItem->$delete_title_name : $model,
                            'disabled' => isset($indexComponent['delete_disabled']) && $indexComponent['delete_disabled'],
                        ])
                    @endif
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
@include('cmf.content.default.table.pagination', [
    'oItems' => $oItems
])
