@extends('cmf.layouts.cmf')

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="brand-card">
                <div class="brand-card-header bg-success" style="height: 3rem;">
                    <i class="{{ \App\Cmf\Project\User\UserController::ICON }}" style="font-size: 14px;"></i>
                    &#160
                    Users
                </div>
                <div class="brand-card-body">
                    <div>
                        <div class="text-value">{{ $data['users']['new'] }}</div>
                        <div class="text-uppercase text-muted small">New per week</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $data['users']['active'] }}</div>
                        <div class="text-uppercase text-muted small">Active</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $data['users']['banned'] }}</div>
                        <div class="text-uppercase text-muted small">Banned</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $data['users']['all'] }}</div>
                        <div class="text-uppercase text-muted small">All</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="brand-card">
                <div class="brand-card-header bg-info" style="height: 3rem;">
                    <i class="{{ \App\Cmf\Project\Listing\ListingController::ICON }}" style="font-size: 14px;"></i>
                    &#160
                    Listings
                </div>
                <div class="brand-card-body">
                    <div>
                        <div class="text-value">{{ $data['listings']['new'] }}</div>
                        <div class="text-uppercase text-muted small">New per week</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $data['listings']['active'] }}</div>
                        <div class="text-uppercase text-muted small">Active</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $data['listings']['banned'] }}</div>
                        <div class="text-uppercase text-muted small">Banned</div>
                    </div>
                    <div>
                        <div class="text-value">{{ $data['listings']['all'] }}</div>
                        <div class="text-uppercase text-muted small">All</div>
                    </div>
                </div>
            </div>
        </div>
{{--        <div class="col-3">--}}
{{--            <div class="brand-card">--}}
{{--                <div class="brand-card-header bg-success" style="height: 3rem;">--}}
{{--                    Reservations--}}
{{--                </div>--}}
{{--                <div class="brand-card-body">--}}
{{--                    <div>--}}
{{--                        <div class="text-value">{{ $data['reservations']['per_week'] }}</div>--}}
{{--                        <div class="text-uppercase text-muted small">New per week</div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <div class="text-value">{{ $data['reservations']['active'] }}</div>--}}
{{--                        <div class="text-uppercase text-muted small">Active</div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <div class="text-value">{{ $data['reservations']['count'] }}</div>--}}
{{--                        <div class="text-uppercase text-muted small">All</div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-3">--}}
{{--            <div class="brand-card">--}}
{{--                <div class="brand-card-header bg-info" style="height: 3rem;">--}}
{{--                    Payments--}}
{{--                </div>--}}
{{--                <div class="brand-card-body">--}}
{{--                    <div>--}}
{{--                        <div class="text-value">{{ $data['payments']['per_week'] }}</div>--}}
{{--                        <div class="text-uppercase text-muted small">New per week</div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <div class="text-value">{{ $data['payments']['active'] }}</div>--}}
{{--                        <div class="text-uppercase text-muted small">Active</div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <div class="text-value">{{ $data['payments']['count'] }}</div>--}}
{{--                        <div class="text-uppercase text-muted small">All</div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-3">--}}
{{--            <div class="brand-card">--}}
{{--                <div class="brand-card-header bg-primary" style="height: 3rem;">--}}
{{--                    Статьи--}}
{{--                </div>--}}
{{--                <div class="brand-card-body">--}}
{{--                    <div>--}}
{{--                        <div class="text-value">{{ $aArticles['count'] ?? 0 }}</div>--}}
{{--                        <div class="text-uppercase text-muted small">Всего</div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <div class="text-value">{{ $aArticles['active'] ?? 0 }}</div>--}}
{{--                        <div class="text-uppercase text-muted small">Активно</div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="{{ \App\Cmf\Project\Reservation\ReservationController::ICON }}"></i>
                    &#160
                    Reservations
                </div>
                <div class="card-body" style="padding: 1rem;">
                    <div class="row">
                        <div class="col-2">
                            <div class="c-callout c-callout-info">
                                <div class="text-muted">Future</div>
                                <div class="text-value-lg">
                                    {{ $data['reservations']['future'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-info">
                                <div class="text-muted">Future Today</div>
                                <div class="text-value-lg">
                                    {{ $data['reservations']['future_today'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-danger">
                                <div class="text-muted">In Process</div>
                                <div class="text-value-lg">
                                    {{ $data['reservations']['beginning'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-danger">
                                <div class="text-muted">Passed</div>
                                <div class="text-value-lg">
                                    {{ $data['reservations']['passed'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-danger">
                                <div class="text-muted">Cancelled</div>
                                <div class="text-value-lg">
                                    {{ $data['reservations']['cancelled'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-danger">
                                <div class="text-muted">All</div>
                                <div class="text-value-lg">
                                    {{ $data['reservations']['all'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="{{ \App\Cmf\Project\Payment\PaymentController::ICON }}"></i>
                    &#160
                    Payments
                </div>
                <div class="card-body" style="padding: 1rem;">
                    <div class="row">
                        <div class="col-2">
                            <div class="c-callout c-callout-info">
                                <div class="text-muted">Dept</div>
                                <div class="text-value-lg">
                                    $ {{ $data['payments']['all']['dept'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-danger">
                                <div class="text-muted">Service Fee</div>
                                <div class="text-value-lg">
                                    $ {{ $data['payments']['all']['service_fee'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-danger">
                                <div class="text-muted">Charges</div>
                                <div class="text-value-lg">$ {{ $data['payments']['charges']['value'] }} ({{$data['payments']['charges']['count'] }})</div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-danger">
                                <div class="text-muted">All</div>
                                <div class="text-value-lg">$ {{ $data['payments']['all']['amount'] }} ({{$data['payments']['all']['count'] }})</div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-danger">
                                <div class="text-muted">Waiting for Payout</div>
                                <div class="text-value-lg">$ {{ $data['reservations']['beginning_transfers_amount'] }}</div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="c-callout c-callout-danger">
                                <div class="text-muted">Payouts</div>
                                <div class="text-value-lg">$ {{ $data['reservations']['passed_payouts_amount'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
