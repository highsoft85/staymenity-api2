@extends('admin.layouts.auth')

@section('body')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-group mb-0">
                    <form class="card p-2 ajax-form" action="{{ routeCmf('register.post') }}">
                        <input type="hidden" name="adminToken" value="{{ md5(str_random(16)) }}">
                        <div class="card-block">
                            <h1>Register</h1>
                            <p class="text-muted">Welcome</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-user"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ trans('cmf/form.name') }}" name="first_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-user"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ trans('cmf/form.email') }}" name="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="{{ trans('cmf/form.password') }}" name="password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="{{ trans('cmf/form.password_confirm') }}" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-2 inner-form-submit">{{ trans('auth.registration') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
                        <div class="card-block text-center" style="display: flex;align-items: center;">
                            <div style="width: 100%;">
                                <h2>{{ $oComposerSite->app->title }}</h2>
                                <p>Панель управления</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
