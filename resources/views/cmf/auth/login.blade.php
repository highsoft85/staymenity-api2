@extends('cmf.layouts.auth')

@section('body')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-group mb-0">
                    <form class="card p-2 ajax-form" action="{{ routeCmf('auth.login.post', ['backTo' => request()->get('backTo') ?? routeCmf('index')]) }}">
                        <input type="hidden" name="adminToken" value="{{ md5(\Illuminate\Support\Str::random(16)) }}">
                        <div class="card-block">
                            <h1>Sign In</h1>
                            <p class="text-muted">Welcome to Admin Dashboard</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-user"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ __('cmf/form.email') }}" name="email" required>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="{{ __('cmf/form.password') }}" name="password" required>
                                </div>
                            </div>
                            <div class="form-group text-right">
{{--                                <a href="{{ routeCmf('auth.password.request') }}">{{ __('auth.forgot_password') }}</a>--}}
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-2 inner-form-submit">{{ __('auth.login') }}</button>
                                </div>
{{--                                @if(!checkEnv(\App\Services\Environment::PRODUCTION))--}}
{{--                                    <small class="col-6 text-muted">--}}
{{--                                        Email: admin@admin.com <br>--}}
{{--                                        Password: 1234567890--}}
{{--                                    </small>--}}
{{--                                @endif--}}
                            </div>
                        </div>
                    </form>
                    <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
                        <div class="card-block text-center" style="display: flex;align-items: center;">
                            <div style="width: 100%;">
                                <img src="{{ asset('svg/main/logo.svg') }}" alt="" width="100%" class="p-2">
{{--                                <h2>{{ $oComposerSite->app->title ?? 'app.title' }}</h2>--}}
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
