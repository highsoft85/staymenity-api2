@php
    $model = $view ?? $model;
@endphp
<div class="modal-header">
    <h4 class="modal-title">Create</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="tab-pane tab-submit active">
        <form class="modal-content dialog__content ajax-form"
              action="{{ routeCmf($model.'.store') }}"
              data-counter=".admin-table-counter"
              data-list=".admin-table.is-{{ $model }}-admin-table"
              data-list-action="{{ routeCmf($model.'.view.post') }}"
              data-callback="closeModalAfterSubmit, refreshAfterSubmit"
              {{ !empty($enctype) ? 'enctype="' . $enctype . '"' : '' }}
              {{ isset($fastEdit) && $fastEdit ? 'data-edit-after-create="1"' : '' }}
              novalidate
        >
            @if(View::exists('cmf.content.' . $model . '.modals.create'))
                @include('cmf.content.' . $model . '.modals.create')
            @else
                @include('cmf.content.default.modals.create')
            @endif
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cmf/modal.close') }}</button>
    <button type="submit" class="btn btn-primary inner-form-submit ajax-link" data-submit-active-tab=".tab-submit">
        @if(isset($submitText))
            {{ $submitText }}
        @else
            {{ __('cmf/modal.save') }}
        @endif
    </button>
</div>
