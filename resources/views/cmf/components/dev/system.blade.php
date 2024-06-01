@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.system'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'System',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-terminal" aria-hidden="true"></i>
                    Активные команды
                </div>
                <div class="card-body" style="padding: 10px">
                    @foreach($commandsExecute as $command)
                        @if(isset($command[1]))
                            <p class="m-0">
                                <code>{{ $command[1] }}</code>
                            </p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    Cron
                    @if($aSchedule['active'])
                        <span class="badge badge-success float-right" style="margin-top: 4px;">Активно</span>
                    @else
                        <span class="badge badge-danger float-right" style="margin-top: 4px;">Не активно</span>
                    @endif
                </div>
                <div class="card-body" style="padding: 10px">
                    <p class="m-0">
                        Если <code>НЕ АКТИВНО</code>, то:
                    </p>
                    <p class="m-0">
                        - Журналы не синхронизируются с конвертером <br>
                        - Не корректно отслеживается активность подписчиков <br>
                        - Не корректно отслеживаются пользователи онлайн <br>
                    </p>
                    <br>
                    <p class="m-0">
                        Время последнего выполнения:
                        @if(!is_null($aSchedule['time']))
                            <code>{{ $aSchedule['time'] }}</code>
                        @else
                            <code>-- -- --</code>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
