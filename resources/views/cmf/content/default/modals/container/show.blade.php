<div class="modal-content dialog__content">
    <div class="modal-header">
        <h4 class="modal-title">Show</h4>
        <div class="text-muted page-desc" style="position: absolute;width: calc(100% - 30px);text-align: right;">
            <div class="col-12">
                @if(isset($title))
                    #{{ $oItem->id }}: {{ $title ?? '' }}
                @else
                    {{ (new \App\Cmf\Project\ModalController())->editTitle($model, $oItem) }}
                @endif
            </div>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        @if(isset($tabs) && is_array($tabs))
            @include('cmf.content.default.tabs.show', ['field_tabs' => $tabs, 'type' => 'show'])
        @else
            @if(View::exists('cmf.content.' . $model . '.modals.show'))
                @include('cmf.content.' . $model . '.modals.show')
            @else
                @include('cmf.content.default.modals.show')
            @endif
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cmf/modal.close') }}</button>
</div>
