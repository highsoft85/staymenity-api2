@if($multiple && isset($oItems))
    <div class="modal-content dialog__content">
        <div class="modal-header">
            <h4 class="modal-title">Промокоды</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="col-md-12 mb-12">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach($oItems as $key => $oItem)
                        <li class="nav-item">
                            <a class="nav-link {{ $key === 0 ? 'active' : '' }}" data-toggle="tab" href="#show-tab-{{ $key }}" role="tab" aria-controls="home" aria-expanded="true">
                                {{ $oItem->promocode }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($oItems as $key => $oItem)
                        <div class="tab-pane {{ $key === 0 ? 'active' : '' }}" id="show-tab-{{ $key }}" role="tabpanel" aria-expanded="true">
                            @include('cmf.content.components.modals.show', [
                                'oItem' => $oItem,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
    </div>
@else
    <div class="col-12">
        <div class="form-group">
            <label class="text-muted m-0">Промокод:</label>
            <div>
                <b>{{ $oItem->promocode }}</b>
            </div>
        </div>
    </div>
@endif
