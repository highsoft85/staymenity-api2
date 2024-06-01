@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.pages'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => __('cmf/layouts/header.profile.system_pages'),
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="padding: 1rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item flex-column align-items-start">
                            <div>
                                <b>Система</b>
                            </div>
                            <p class="mb-1 text-muted">
                                Состояние Broadcasting, Очередей и Крона
                            </p>
                            <p class="mb-0">
                                <a href="{{ routeCmf('dev.system.index') }}">Перейти</a>
                            </p>
                        </li>
                        <li class="list-group-item flex-column align-items-start">
                            <div>
                                <b>Очереди</b>
                            </div>
                            <p class="mb-1 text-muted">
                                Очистка очередей
                            </p>
                            <p class="mb-0">
                                <a href="{{ routeCmf('dev.queue.index') }}">Перейти</a>
                            </p>
                        </li>
                        <li class="list-group-item flex-column align-items-start">
                            <div>
                                <b>Письма</b>
                            </div>
                            <p class="mb-1 text-muted">
                                Рендер шаблонов писем
                            </p>
                            <p class="mb-0">
                                <a href="{{ routeCmf('dev.mail.index') }}">Перейти</a>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
