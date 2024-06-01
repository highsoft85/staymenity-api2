@extends('cmf.layouts.cmf')

@section('content')
    <div class="row">
        <div class="col-2">
            <div class="brand-card">
                <div class="brand-card-header bg-success" style="height: 3rem;">
                    <i class="{{ \App\Cmf\Project\User\UserController::ICON }}"></i>
                    &#160
                    <a href="{{ routeCmf('statistic.user') }}" class="text-white">Пользователи</a>
                </div>
                <div class="brand-card-body">
                    <div>
                        <div class="text-value">{{ $aUsers['count'] }}</div>
                        <div class="text-uppercase text-muted small">Всего</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $aUsers['active'] }}</div>
                        <div class="text-uppercase text-muted small">Активно</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="brand-card">
                <div class="brand-card-header bg-success" style="height: 3rem;">
                    <i class="{{ \App\Cmf\Project\Tour\TourController::ICON }}"></i>
                    &#160
                    Туры
                </div>
                <div class="brand-card-body">
                    <div>
                        <div class="text-value">{{ $aTours['count'] }}</div>
                        <div class="text-uppercase text-muted small">Всего</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $aTours['active'] }}</div>
                        <div class="text-uppercase text-muted small">Активно</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="brand-card">
                <div class="brand-card-header bg-info" style="height: 3rem;">
                    <i class="{{ \App\Cmf\Project\Organization\OrganizationController::ICON }}"></i>
                    &#160
                    Организаторы
                </div>
                <div class="brand-card-body">
                    <div>
                        <div class="text-value">{{ $aOrganizations['count'] }}</div>
                        <div class="text-uppercase text-muted small">Всего</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $aOrganizations['active'] }}</div>
                        <div class="text-uppercase text-muted small">Активно</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="brand-card">
                <div class="brand-card-header bg-success" style="height: 3rem;">
                    <i class="{{ \App\Cmf\Project\Direction\DirectionController::ICON }}"></i>
                    &#160
                    Направления
                </div>
                <div class="brand-card-body">
                    <div>
                        <div class="text-value">{{ $aDirections['count'] }}</div>
                        <div class="text-uppercase text-muted small">Всего</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $aDirections['active'] }}</div>
                        <div class="text-uppercase text-muted small">Активно</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="brand-card">
                <div class="brand-card-header bg-warning" style="height: 3rem;">
                    <i class="{{ \App\Cmf\Project\News\NewsController::ICON }}"></i>
                    &#160
                    Новости
                </div>
                <div class="brand-card-body">
                    <div>
                        <div class="text-value">{{ $aNews['count'] }}</div>
                        <div class="text-uppercase text-muted small">Всего</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $aNews['active'] }}</div>
                        <div class="text-uppercase text-muted small">Активно</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="brand-card">
                <div class="brand-card-header bg-primary" style="height: 3rem;">
                    <i class="{{ \App\Cmf\Project\Article\ArticleController::ICON }}"></i>
                    &#160
                    Статьи
                </div>
                <div class="brand-card-body">
                    <div>
                        <div class="text-value">{{ $aArticles['count'] }}</div>
                        <div class="text-uppercase text-muted small">Всего</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $aArticles['active'] }}</div>
                        <div class="text-uppercase text-muted small">Активно</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="padding: 1rem;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="callout callout-info">
                                        <small class="text-muted">Туры в этом месяце</small>
                                        <br>
                                        <strong class="h4">{{ $aTours['month'] }}</strong>
                                        <div class="chart-wrapper">
                                            <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="callout callout-danger">
                                        <small class="text-muted">Туры после этого месяца</small>
                                        <br>
                                        <strong class="h4">{{ $aTours['after_month'] }}</strong>
                                        <div class="chart-wrapper">
                                            <canvas id="sparkline-chart-2" width="100" height="30"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <div class="progress-group mb-4">
                                <div class="progress-group-header align-items-end">
                                    <div>Туры в этом месяце</div>
                                    <div class="ml-auto font-weight-bold mr-1">{{ $aTours['month'] }}</div>
                                    <div class="text-muted small">({{ progressWidth($aTours['active'], $aTours['month']) }}%)</div>
                                </div>
                                <div class="progress-group-bars">
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ progressWidth($aTours['active'], $aTours['month']) }}%" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-group mb-4">
                                <div class="progress-group-header align-items-end">
                                    <div>Туры после этого месяца</div>
                                    <div class="ml-auto font-weight-bold mr-1">{{ $aTours['after_month'] }}</div>
                                    <div class="text-muted small">({{ progressWidth($aTours['active'], $aTours['after_month']) }}%)</div>
                                </div>
                                <div class="progress-group-bars">
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ progressWidth($aTours['active'], $aTours['after_month']) }}%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="callout callout-warning">
                                        <small class="text-muted">Категорий</small>
                                        <br>
                                        <strong class="h4">{{ $aCategories['active'] }} / {{ $aCategories['count'] }}</strong>
                                        <div class="chart-wrapper">
                                            <canvas id="sparkline-chart-3" width="100" height="30"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="callout callout-success">
                                        <small class="text-muted">Развлечений</small>
                                        <br>
                                        <strong class="h4">{{ $aEntertainments['active'] }} / {{ $aEntertainments['count'] }}</strong>
                                        <div class="chart-wrapper">
                                            <canvas id="sparkline-chart-4" width="100" height="30"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="mt-0">
{{--                            <div class="progress-group">--}}
{{--                                <div class="progress-group-header">--}}
{{--                                    <i class="icon-user progress-group-icon"></i>--}}
{{--                                    <div>Male</div>--}}
{{--                                    <div class="ml-auto font-weight-bold">43%</div>--}}
{{--                                </div>--}}
{{--                                <div class="progress-group-bars">--}}
{{--                                    <div class="progress progress-xs">--}}
{{--                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 43%" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100"></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
