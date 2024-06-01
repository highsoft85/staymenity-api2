@extends('cmf.layouts.auth')

@section('body')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-group mb-0">
                    <form class="card p-2 ajax-form" action="{{ routeCmf('auth.password.reset.post') }}">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="card-block">
                            <h1>Reset Password</h1>
                            <p class="text-muted">New password</p>
                            <div class="form-group is-disabled" style="position: relative">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-user"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ trans('form.email') }}" name="email" value="{{ $email }}" required style="z-index: 0;">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="{{ __('form.password') }}" name="password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="{{ __('form.password_confirm') }}" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="form-group text-left">
                                <a href="{{ routeCmf('auth.login') }}">Back to Sign In</a>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-2 inner-form-submit">{{ __('auth.rest_password') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
                        <div class="card-block text-center" style="display: flex;align-items: center;">
                            <div style="width: 100%;">
                                <h2>{{ $oComposerSite->app->title ?? 'app.title' }}</h2>
                                <p>Admin Dashboard</p>
                                <p class="text-muted">v{{ config('cmf.version') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
