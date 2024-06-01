<div class="modal-content dialog__content">
    <div class="modal-header">
        <h4 class="modal-title">System settings</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-md-12 mb-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#user-commands-user-1" role="tab" aria-controls="user-commands-1" aria-expanded="true"
                       data-hidden-submit="1"
                    >
                        Console commands
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#user-commands-user-2" role="tab" aria-controls="user-commands-2" aria-expanded="true"
                       data-hidden-submit="1"
                    >
                        Configuration
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane tab-submit active" id="user-commands-user-1" role="tabpanel" aria-expanded="true">
                    <div class="row">
                        <div class="hr-label">
                            <label>Cache</label>
                            <hr>
                        </div>
                        <div class="col-12">
                            <a class="btn btn-danger" href="{{ routeCmf('dev.command.index', ['name' => 'cache:clear']) }}">Clear</a>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="hr-label">
                            <label>
                                Views
                                {{--<i class="fa fa-circle-o"></i>--}}
                            </label>
                            <hr>
                        </div>
                        <div class="col-12">
                            <a class="btn btn-success" href="{{ routeCmf('dev.command.index', ['name' => 'view:cache']) }}">Cache</a>
                            <a class="btn btn-danger" href="{{ routeCmf('dev.command.index', ['name' => 'view:clear']) }}">Clear</a>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="hr-label">
                            <label>
                                Config
                                {{--<i class="fa fa-cog"></i>--}}
                            </label>
                            <hr>
                        </div>
                        <div class="col-12">
                            <a class="btn btn-success" href="{{ routeCmf('dev.command.index', ['name' => 'config:cache']) }}">Cache</a>
                            <a class="btn btn-danger" href="{{ routeCmf('dev.command.index', ['name' => 'config:clear']) }}">Clear</a>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="hr-label">
                            <label>
                                Routes
                                {{--<i class="fa fa-cog"></i>--}}
                            </label>
                            <hr>
                        </div>
                        <div class="col-12">
                            <a class="btn btn-success" href="{{ routeCmf('dev.command.index', ['name' => 'route:cache']) }}">Cache</a>
                            <a class="btn btn-danger" href="{{ routeCmf('dev.command.index', ['name' => 'route:clear']) }}">Clear</a>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="hr-label">
                            <label>
                                Update /data
                                {{--<i class="fa fa-cog"></i>--}}
                            </label>
                            <hr>
                        </div>
                        <div class="col-12">
                            <a class="btn btn-success" href="{{ routeCmf('dev.command.index', ['name' => 'cache:data-update']) }}">Update</a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane tab-submit" id="user-commands-user-2" role="tabpanel" aria-expanded="true">
                    <div class="row" style="border-bottom: 1px solid #eee;">
                        <div class="col-md-3 col-sm-12 mbt-5">
                            <b>Environment</b>
                        </div>
                        <div class="col-md-9 col-sm-12 mbt-5">
                            <code>{{ config('app.env') }}</code>
                        </div>
                    </div>
                    <div class="row" style="border-bottom: 1px solid #eee;">
                        <div class="col-md-3 col-sm-12 mbt-5">
                            <b>Url</b>
                        </div>
                        <div class="col-md-9 col-sm-12 mbt-5">
                            <code>{{ config('app.url') }}</code>
                        </div>
                    </div>
                    <div class="row" style="border-bottom: 1px solid #eee;">
                        <div class="col-md-3 col-sm-12 mbt-5">
                            <b>Web Url</b>
                        </div>
                        <div class="col-md-9 col-sm-12 mbt-5">
                            <code>{{ config('app.web_url') }}</code>
                        </div>
                    </div>
                    <div class="row" style="border-bottom: 1px solid #eee;">
                        <div class="col-md-3 col-sm-12 mbt-5">
                            <b>Admin Url</b>
                        </div>
                        <div class="col-md-9 col-sm-12 mbt-5">
                            <code>{{ config('cmf.url') }}</code>
                        </div>
                    </div>
                    <div class="row" style="border-bottom: 1px solid #eee;">
                        <div class="col-md-3 col-sm-12 mbt-5">
                            <b>Api Url</b>
                        </div>
                        <div class="col-md-9 col-sm-12 mbt-5">
                            <code>{{ config('api.url') }}</code>
                        </div>
                    </div>
                    <div class="row" style="border-bottom: 1px solid #eee;">
                        <div class="col-md-3 col-sm-12 mbt-5">
                            <b>Timezone</b>
                        </div>
                        <div class="col-md-9 col-sm-12 mbt-5">
                            <code>{{ config('app.timezone') }}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
