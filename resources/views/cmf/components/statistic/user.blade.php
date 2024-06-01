@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'statistic.user'
])

@section('content')
    @component('cmf.components.table.index')
        <div class="admin-table table-component-pagination">
            <table class="table table-admin table--mobile table-hover">
                <thead>
                <tr>
                    <th class="is-id">#</th>
                    <th class="is-image"></th>
                    <th class="is-first_name">Имя</th>
                    <th class="is-roles">Роль</th>
                    <th class="is-categories">
                        <i class="icon-heart"></i>
                    </th>
                    <th class="is-categories">
                        <i class="icon-envelope"></i>
                    </th>
                    <th class="is-categories">
                        <i class="icon-speech"></i>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th class="is-id">#</th>
                    <th class="is-image"></th>
                    <th class="is-first_name">Имя</th>
                    <th class="is-roles">Роль</th>
                    <th class="is-categories">
                        <i class="icon-heart"></i>
                    </th>
                    <th class="is-categories">
                        <i class="icon-envelope"></i>
                    </th>
                    <th class="is-categories">
                        <i class="icon-speech"></i>
                    </th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($oUsers as $oUser)
                    <tr>
                        <td class="is-id">
                            {{ $oUser->id }}
                        </td>
                        <td class="is-image">
                            @include('cmf.content.default.table.column.avatar', [
                                'oItem' => $oUser,
                                'model' => 'user',
                            ])
                        </td>
                        <td class="is-first_name">
                            {{ $oUser->first_name }} {{ $oUser->last_name }}
                        </td>
                        <td class="is-roles">
                            @include('cmf.content.user.table.column.roles', [
                                'item' => $oUser,
                            ])
                        </td>
                        <td class="is-categories">
                            <span class="badge badge-pill badge-{{ $oUser->favouriteTours()->count() !== 0 ? 'success' : 'default' }}"
                                  data-tippy-popover
                                  data-tippy-content="Избранных Туров"
                            >
                                {{ $oUser->favouriteTours()->count() }}
                            </span>
                            <span class="badge badge-pill badge-{{ $oUser->favouriteDirections()->count() !== 0 ? 'success' : 'default' }}"
                                  data-tippy-popover
                                  data-tippy-content="Избранных Направлений"
                            >
                                {{ $oUser->favouriteDirections()->count() }}
                            </span>
                            <span class="badge badge-pill badge-{{ $oUser->favouriteOrganizations()->count() !== 0 ? 'success' : 'default' }}"
                                  data-tippy-popover
                                  data-tippy-content="Избранных Организаторов"
                            >
                                {{ $oUser->favouriteOrganizations()->count() }}
                            </span>
                        </td>
                        <td class="is-categories">
                            <span class="badge badge-pill badge-{{ $oUser->subscriptionsDirections()->count() !== 0 ? 'success' : 'default' }}"
                                  data-tippy-popover
                                  data-tippy-content="Подписок Направлений"
                            >
                                {{ $oUser->subscriptionsDirections()->count() }}
                            </span>
                            <span class="badge badge-pill badge-{{ $oUser->subscriptionsOrganizations()->count() !== 0 ? 'success' : 'default' }}"
                                  data-tippy-popover
                                  data-tippy-content="Подписок Организаторов"
                            >
                                {{ $oUser->subscriptionsOrganizations()->count() }}
                            </span>
                        </td>
                        <td class="is-categories">
                            <span class="badge badge-pill badge-{{ $oUser->commentsOrganizations()->count() !== 0 ? 'success' : 'default' }}"
                                  data-tippy-popover
                                  data-tippy-content="Комментарий Организаторов"
                            >
                                {{ $oUser->commentsOrganizations()->count() }}
                            </span>
                            <span class="badge badge-pill badge-{{ $oUser->commentsDirections()->count() !== 0 ? 'success' : 'default' }}"
                                  data-tippy-popover
                                  data-tippy-content="Комментарий Направлений"
                            >
                                {{ $oUser->commentsDirections()->count() }}
                            </span>
                            <span class="badge badge-pill badge-{{ $oUser->commentsNews()->count() !== 0 ? 'success' : 'default' }}"
                                  data-tippy-popover
                                  data-tippy-content="Комментарий Новостей"
                            >
                                {{ $oUser->commentsNews()->count() }}
                            </span>
                            <span class="badge badge-pill badge-{{ $oUser->commentsArticles()->count() !== 0 ? 'success' : 'default' }}"
                                  data-tippy-popover
                                  data-tippy-content="Комментарий Статей"
                            >
                                {{ $oUser->commentsArticles()->count() }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endcomponent
@endsection
