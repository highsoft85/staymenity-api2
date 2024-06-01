@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.queue'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Queue',
        'description' => '',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    Mail
                    @if($aCommands[\App\Jobs\QueueCommon::QUEUE_NAME_MAIL]['active'])
                        <span class="badge badge-success float-right" style="margin-top: 4px;">Активно</span>
                    @else
                        <span class="badge badge-danger float-right" style="margin-top: 4px;">Не активно</span>
                    @endif
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        Если <code>НЕ АКТИВНО</code>, email не будут рассылаться по очередям
                    </p>
                    <p class="m-0">
                        - После регистрации <br>
                        - Сброс пароля <br>
                    </p>
                    <br>
                    <p class="m-0">
                        Команда: <code>{{ $aCommands[\App\Jobs\QueueCommon::QUEUE_NAME_MAIL]['command'] }}</code>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    Notification
                    @if($aCommands[\App\Jobs\QueueCommon::QUEUE_NAME_NOTIFICATION]['active'])
                        <span class="badge badge-success float-right" style="margin-top: 4px;">Активно</span>
                    @else
                        <span class="badge badge-danger float-right" style="margin-top: 4px;">Не активно</span>
                    @endif
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        Если <code>НЕ АКТИВНО</code>, то не будут приходить сообщения в SLACK
                    </p>
                    <p class="m-0">
                        - Добавление в счетчик firebase будет выполняться сразу <br>
                    </p>
                    <br>
                    <p class="m-0">
                        Команда: <code>{{ $aCommands[\App\Jobs\QueueCommon::QUEUE_NAME_NOTIFICATION]['command'] }}</code>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    SMS
                    @if($aCommands[\App\Jobs\QueueCommon::QUEUE_NAME_SMS]['active'])
                        <span class="badge badge-success float-right" style="margin-top: 4px;">Активно</span>
                    @else
                        <span class="badge badge-danger float-right" style="margin-top: 4px;">Не активно</span>
                    @endif
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        Если <code>НЕ АКТИВНО</code>, то не будут отправлять не через очередь
                    </p>
                    <br>
                    <p class="m-0">
                        Команда: <code>{{ $aCommands[\App\Jobs\QueueCommon::QUEUE_NAME_SMS]['command'] }}</code>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Очистка событий и задач
                </div>
                <div class="card-body" style="padding: 10px">
                    <div class="d-flex align-items-center mb-1">
                        <a class="btn btn-sm btn-danger" href="{{ routeCmf('dev.command.index', ['name' => 'queue:clear --connection=redis --queue=default']) }}">
                            Очистить
                        </a>
                        &nbsp;&nbsp;
                        <div>
                            Default - <code>queue=default</code>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <a class="btn btn-sm btn-danger" href="{{ routeCmf('dev.command.index', ['name' => 'queue:clear --connection=redis --queue=' . \App\Jobs\QueueCommon::QUEUE_NAME_MAIL]) }}">
                            Очистить
                        </a>
                        &nbsp;&nbsp;
                        <div>
                            Mail - <code>queue={{ \App\Jobs\QueueCommon::QUEUE_NAME_MAIL }}</code>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <a class="btn btn-sm btn-danger" href="{{ routeCmf('dev.command.index', ['name' => 'queue:clear --connection=redis --queue=' . \App\Jobs\QueueCommon::QUEUE_NAME_NOTIFICATION]) }}">
                            Очистить
                        </a>
                        &nbsp;&nbsp;
                        <div>
                            Notification - <code>queue={{ \App\Jobs\QueueCommon::QUEUE_NAME_NOTIFICATION }}</code>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <a class="btn btn-sm btn-danger" href="{{ routeCmf('dev.command.index', ['name' => 'queue:clear --connection=redis --queue=' . \App\Jobs\QueueCommon::QUEUE_NAME_SMS]) }}">
                            Очистить
                        </a>
                        &nbsp;&nbsp;
                        <div>
                            SMS - <code>queue={{ \App\Jobs\QueueCommon::QUEUE_NAME_SMS }}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
