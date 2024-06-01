@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.mail'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Mail',
        'description' => '',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Электронные письма
                </div>
                <div class="card-body" style="padding: 10px">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-sm {{ isset($name) && $name === \App\Mail\Auth\RegisteredMail::NAME ? 'btn-primary' : 'btn-link' }} mr-1"
                           href="{{ routeCmf('dev.mail.index', ['name' => \App\Mail\Auth\RegisteredMail::NAME]) }}"
                        >
                            Регистрация
                        </a>
                        <a class="btn btn-sm {{ isset($name) && $name === \App\Mail\Auth\ResetPasswordMail::NAME ? 'btn-primary' : 'btn-link' }} mr-1"
                           href="{{ routeCmf('dev.mail.index', ['name' => \App\Mail\Auth\ResetPasswordMail::NAME]) }}"
                        >
                            Сброс пароля
                        </a>
                        <a class="btn btn-sm {{ isset($name) && $name === \App\Mail\Auth\VerifyAccountMail::NAME ? 'btn-primary' : 'btn-link' }} mr-1"
                           href="{{ routeCmf('dev.mail.index', ['name' => \App\Mail\Auth\VerifyAccountMail::NAME]) }}"
                        >
                            Подтверждение
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($name))
            <div class="col-12">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive" src="{{ routeCmf('dev.mail.name.index', ['name' => $name]) }}" height="500px"></iframe>
                </div>
            </div>
        @endif
    </div>
@endsection
