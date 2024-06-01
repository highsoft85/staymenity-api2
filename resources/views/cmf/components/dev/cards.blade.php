@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.cards'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Карточки',
        'description' => '',
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
                                <b>Туры</b>
                            </div>
                            <p class="mb-1 text-muted">
                                Карточки тура
                            </p>
                            <p class="mb-0">
                                <a href="{{ routeCmf('dev.cards.tours.index') }}">Перейти</a>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
