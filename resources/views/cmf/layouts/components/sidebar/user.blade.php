<img src="{{ $oComposerMember->get('user.image') }}"
     data-user-image
     class="img-avatar"
     alt="Avatar"
>
<div class="sidebar-name">
    <strong data-user-name>
        {{ $oComposerMember->get('user.first_name') }} {{ $oComposerMember->get('user.last_name') }}
    </strong>
</div>
<div class="text-muted">
    @foreach($oComposerMember->get('user.roles') as $role)
        <small>{{ $role['title'] }}{{ $loop->last ? '' : ' / ' }}</small>
    @endforeach
</div>
<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    @if(member()->isSuperAdmin())
        <button type="button" class="btn btn-link trigger" data-dialog="#user-settings-command-edit-modal" data-modal>
            <i class="icon-settings"></i>
        </button>
    @endif
</div>
