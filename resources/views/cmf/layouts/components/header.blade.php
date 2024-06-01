<header class="app-header navbar">
    <a class="navbar-brand" href="{{ config('app.url') }}" style="background-size: 140px auto;background: #141519;color: #fff;text-align: center;font-size: 16px;line-height: 33px;">
        <img src="{{ asset('svg/main/logo.svg') }}" alt="" width="100%">
    </a>

    <ul class="nav navbar-nav hidden-md-down">
        <li class="nav-item">
            <a class="nav-link navbar-toggler sidebar-toggler"
               data-url="{{ routeCmf('user.action.post', ['name' => 'saveSidebarToggle']) }}"
               href="#"
            >☰</a>
        </li>
    </ul>

    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item hidden-md-down">
            <a class="btn btn-link text-success" href="{{ routeCmf('feedback.index') }}"
               data-tippy-popover
               data-tippy-content="Active Feedbacks"
            >
                <i class="icon-feed"></i>
                @if((int)$composerFeedbackCount !== 0)
                    <span class="badge badge-pill badge-success">{{ $composerFeedbackCount }}</span>
                @endif
            </a>
        </li>
        @if(!healthCheckHostfully()->isActive())
            <li class="nav-item hidden-md-down">
                <a class="btn btn-link text-danger" href="#"
                   data-tippy-popover
                   data-tippy-content="Hostfully is not working"
                >
                    <i class="fa fa-houzz"></i>
                </a>
            </li>
        @endif
        <li class="nav-item hidden-md-down">
            <a class="btn btn-link text-success" href="{{ routeCmf('request.index') }}"
               data-tippy-popover
               data-tippy-content="New Requests"
            >
                <i class="icon-action-redo"></i>
                @if((int)$composerRequestCount !== 0)
                    <span class="badge badge-pill badge-success">{{ $composerRequestCount }}</span>
                @endif
            </a>
        </li>
        @if(isDeveloperMode())
            <li class="nav-item hidden-md-down">
                <a class="btn btn-link text-success" href="#"
                   data-tippy-popover
                   data-tippy-content="Developer Mode"
                >
                    <i class="fa fa-rss" aria-hidden="true"></i>
                </a>
            </li>
        @endif
        <li class="nav-item dropdown" style="padding-right: 20px;">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <img src="{{ $oComposerMember->get('user.image') }}"
                     data-user-image
                     class="img-avatar"
                     alt="{{ $oComposerMember->get('user.email') }}"
                >
                <span class="hidden-md-down" data-user-name>{{ member()->user()->first_name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item trigger"
                   href="#"
                   data-ajax
                   data-dialog="#custom-edit-modal"
                   data-action="{{ routeCmf('user.edit.modal.post', ['id' => $oComposerMember->get('user.id')]) }}"
                   data-ajax-init="uploader"
                >
                    <i class="fa fa-pencil"></i>
                    {{ __('cmf/layouts/header.profile.edit') }}
                </a>
                @if(member()->isSuperAdmin() && config('horizon.enabled'))
                    <a class="dropdown-item" href="{{ route('horizon.index') }}">
                        <i class="fa fa-tasks"></i>
                        {{ __('cmf/layouts/header.profile.queues_manager') }}
                    </a>
                @endif
                <a class="dropdown-item ajax-link" href="#" action="{{ routeCmf('logout.post') }}">
                    <i class="fa fa-lock"></i>
                    {{ __('cmf/layouts/header.profile.logout') }}
                </a>
            </div>
        </li>
    </ul>
</header>
