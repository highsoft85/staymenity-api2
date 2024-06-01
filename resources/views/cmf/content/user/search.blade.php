@extends('cmf.layouts.cmf')

@section('content.title')
    @include('cmf.content.default.table.title')
@endsection
@section('breadcrumb-search')

@endsection
@section('breadcrumb-right')

@endsection

@section('content')
    @component('cmf.components.table.index')
        <div class="">
            <form class="row ajax-form" data-loading="1" data-callback="apiDataDump"
                  data-view=".--search-dd"
                  action="{{ routeCmf($model . '.action.post', ['name' => 'actionSearch']) }}"
            >
                <div style="width: 500px;">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'role',
                                        'item' => null,
                                        'field' => field()->userSearch('role'),
                                    ])
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'from_at',
                                        'item' => null,
                                        'field' => field()->userSearch('from_at'),
                                    ])
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'to_at',
                                        'item' => null,
                                        'field' => field()->userSearch('to_at'),
                                    ])
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'purpose',
                                        'item' => null,
                                        'field' => field()->userSearch('purpose'),
                                    ])
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'seek_groups_couples',
                                        'item' => null,
                                        'field' => field()->userSearch('seek_groups_couples'),
                                    ])
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'seek_groups_families',
                                        'item' => null,
                                        'field' => field()->userSearch('seek_groups_families'),
                                    ])
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'seek_groups_friends',
                                        'item' => null,
                                        'field' => field()->userSearch('seek_groups_friends'),
                                    ])
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'gender',
                                        'item' => null,
                                        'field' => field()->userSearch('gender'),
                                    ])
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'languages',
                                        'item' => null,
                                        'field' => field()->userSearch('languages'),
                                    ])
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    @include('cmf.content.default.form.default', [
                                        'name' => 'hobbies',
                                        'item' => null,
                                        'field' => field()->userSearch('hobbies'),
                                    ])
                                </div>
                            </div>



                            {{--                        <div class="col-12">--}}
                            {{--                            <div class="form-group">--}}
                            {{--                                @include('cmf.content.default.form.default', [--}}
                            {{--                                    'name' => 'role',--}}
                            {{--                                    'item' => null,--}}
                            {{--                                    'field' => field()->userSearch('country'),--}}
                            {{--                                ])--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button role="button" class="btn btn-primary inner-form-submit" href="#">
                        Найти
                    </button>
                </div>
            </form>
        </div>
    @endcomponent
    <div class="--search-dd"></div>
@endsection
