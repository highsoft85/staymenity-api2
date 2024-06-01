@extends('cmf.layouts.auth')

@section('body')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-group mb-0">
                    <form class="card p-2 ajax-form--" action="{{ routeCmf('auth.login.post', ['backTo' => request()->get('backTo') ?? routeCmf('index')]) }}">
                        <input type="hidden" name="adminToken" value="{{ md5(\Illuminate\Support\Str::random(16)) }}">
                        <div class="card-block">
                            <h1>Вход</h1>
                            <p class="text-muted">Добро пожаловать</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-user"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ __('form.email') }}" name="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="{{ __('form.password') }}" name="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary px-2 --sanctum-admin-submit --login">{{ __('auth.login') }}</button>
                                </div>
                                @if(!checkEnv(\App\Services\Environment::PRODUCTION))
                                    <small class="col-6 text-muted">
                                        Email: admin@admin.com <br>
                                        Password: 1234567890
                                    </small>
                                @endif
                            </div>
                        </div>
                    </form>
                    <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
                        <div class="card-block text-center" style="display: flex;align-items: center;">
                            <div style="width: 100%;">
                                <h2>{{ $oComposerSite->app->title ?? 'app.title' }}</h2>
                                <p>{{ $oComposerSite->app->title_short ?? 'Панель управления' }}</p>
                                <p>Версия приложения: {{ config('cmf.version') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-group mb-0">
                    <form class="card p-2 ajax-form--" action="{{ routeCmf('auth.login.post', ['backTo' => request()->get('backTo') ?? routeCmf('index')]) }}">
                        <input type="hidden" name="adminToken" value="{{ md5(\Illuminate\Support\Str::random(16)) }}">
                        <div class="card-block">
                            <h1>Регистрация</h1>
                            <p class="text-muted">Добро пожаловать</p>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="traveller" name="role" value="traveller" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="gender" name="gender" value="1" required>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-user"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ __('form.email') }}" name="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="{{ __('form.password') }}" name="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary px-2 --sanctum-admin-submit --register">{{ __('auth.registration') }}</button>
                                </div>
                                @if(!checkEnv(\App\Services\Environment::PRODUCTION))
                                    <small class="col-6 text-muted">
                                        Email: admin@admin.com <br>
                                        Password: 1234567890
                                    </small>
                                @endif
                            </div>
                        </div>
                    </form>
                    <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
                        <div class="card-block text-center" style="display: flex;align-items: center;">
                            <div style="width: 100%;">
                                <h2>{{ $oComposerSite->app->title ?? 'app.title' }}</h2>
                                <p>{{ $oComposerSite->app->title_short ?? 'Панель управления' }}</p>
                                <p>Версия приложения: {{ config('cmf.version') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-group mb-0">
                    <form class="card p-2 ajax-form--" action="{{ routeCmf('auth.login.post', ['backTo' => request()->get('backTo') ?? routeCmf('index')]) }}">
                        <input type="hidden" name="adminToken" value="{{ md5(\Illuminate\Support\Str::random(16)) }}">
                        <div class="card-block">
                            <h1>Сброс пароля</h1>
                            <p class="text-muted">Добро пожаловать</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-user"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ __('form.email') }}" name="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary px-2 --sanctum-admin-submit --password-email">{{ __('auth.send_reset_link') }}</button>
                                </div>
                                @if(!checkEnv(\App\Services\Environment::PRODUCTION))
                                    <small class="col-6 text-muted">
                                        Email: admin@admin.com <br>
                                        Password: 1234567890
                                    </small>
                                @endif
                            </div>
                        </div>
                    </form>
                    <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
                        <div class="card-block text-center" style="display: flex;align-items: center;">
                            <div style="width: 100%;">
                                <h2>{{ $oComposerSite->app->title ?? 'app.title' }}</h2>
                                <p>{{ $oComposerSite->app->title_short ?? 'Панель управления' }}</p>
                                <p>Версия приложения: {{ config('cmf.version') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-group mb-0">
                    <form class="card p-2 ajax-form--" action="{{ routeCmf('auth.login.post', ['backTo' => request()->get('backTo') ?? routeCmf('index')]) }}">
                        <input type="hidden" name="adminToken" value="{{ md5(\Illuminate\Support\Str::random(16)) }}">
                        <div class="card-block">
                            <h1>Новый пароль</h1>
                            <p class="text-muted">Добро пожаловать</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-user"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ __('form.token') }}" name="token" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-user"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ __('form.email') }}" name="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="{{ __('form.password') }}" name="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary px-2 --sanctum-admin-submit --password-reset">{{ __('auth.rest_password') }}</button>
                                </div>
                                @if(!checkEnv(\App\Services\Environment::PRODUCTION))
                                    <small class="col-6 text-muted">
                                        Email: admin@admin.com <br>
                                        Password: 1234567890
                                    </small>
                                @endif
                            </div>
                        </div>
                    </form>
                    <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
                        <div class="card-block text-center" style="display: flex;align-items: center;">
                            <div style="width: 100%;">
                                <h2>{{ $oComposerSite->app->title ?? 'app.title' }}</h2>
                                <p>{{ $oComposerSite->app->title_short ?? 'Панель управления' }}</p>
                                <p>Версия приложения: {{ config('cmf.version') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
