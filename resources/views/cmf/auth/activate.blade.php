@extends('admin.layouts.auth')

@section('content')
    <div class="card-group mb-0">
        <form class="card p-2 ajax-form" action="{{ routeCmf('activate.post') }}">
            <div class="card-block">
                <h1>Вход</h1>
                <p class="text-muted">Активационный код</p>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-user"></i></span>
                        <input type="text" class="form-control" placeholder="Код" name="code" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary px-2 inner-form-submit">{{ trans('auth.activate') }}</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
            <div class="card-block text-center" style="display: flex;align-items: center;">
                <div style="width: 100%;">
                    <h2>{{ $oComposerSite->app->title }}</h2>
                    <p>Активационный код</p>
                </div>
            </div>
        </div>
    </div>
@endsection
