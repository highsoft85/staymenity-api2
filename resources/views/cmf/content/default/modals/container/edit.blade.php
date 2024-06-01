<div id="modal--edit-container" class="modal-content dialog__content {{ $oItem->multi ? '--save-multi' : '' }}">
    <div class="modal-header">
        <h4 class="modal-title">Edit</h4>
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
        <div class="row">
            <div class="col-md-12 mb-12 {{ isset($tabsScrolling) && $tabsScrolling ? 'nav-tabs-scrolling-container' : '' }}">
                @if(!empty($tabs))
                    @foreach($tabs as $key => $tab)
                        @if($loop->first)
                            <ul class="nav nav-tabs" role="tablist">
                        @endif
                        <li class="nav-item {{ isset($tab['hidden']) && $tab['hidden'] ? 'hidden' : '' }}">
                        <a class="nav-link @if($loop->first)active @endif" data-toggle="tab" href="#user-edit-tab-{{ $loop->index }}" role="tab"
                            @php
                            if (!isset($tab['tabs_attributes'])) {
                                $tab['tabs_attributes'] = [
                                    'aria-controls' => 'tab-' . $key,
                                    'aria-expanded' => 'true',
                                    'data-hidden-submit' => 1,
                                ];
                            }
                            @endphp
                            @foreach($tab['tabs_attributes'] as $attribute => $value)
                                {{ $attribute }} = "{{ $value }}"
                            @endforeach
                        >{{ $tab['title'] }}</a>
                        </li>
                        @if($loop->last)
                            </ul>
                        @endif
                    @endforeach
                @endif
                @if(!empty($tabs))
                    @foreach($tabs as $key => $tab)
                        @if($loop->first)
                            <div class="tab-content">
                        @endif
                        <div class="tab-pane tab-submit @if($loop->first)active @endif" id="user-edit-tab-{{ $loop->index }}" role="tabpanel"
                            @php
                                if (!isset($tab['content_attributes'])) {
                                    $tab['content_attributes'] = [
                                        'aria-expanded' => 'false',
                                    ];
                                }
                            @endphp
                            @foreach($tab['content_attributes'] as $attribute => $value)
                                {{ $attribute }} = "{{ $value }}"
                            @endforeach
                        >
                            @if(View::exists('cmf.content.' . $model . '.modals.'.$key))
                                @include('cmf.content.' . $model . '.modals.'.$key)
                            @else
                                @include('cmf.content.default.modals.'.$key)
                            @endif
                        </div>
                        @if($loop->last)
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="tab-pane tab-submit active">
                        @if(View::exists('cmf.content.' . $model . '.modals.edit'))
                            @include('cmf.content.' . $model . '.modals.edit')
                        @else
                            @include('cmf.content.default.modals.edit')
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer" style="position: relative;">
        @if($oItem->multi)
            @include('cmf.content.default.modals.container.components.multiSave')
        @endif
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cmf/modal.close') }}</button>
        <button type="button" class="btn btn-primary ajax-link" data-submit-active-tab=".tab-submit">{{ __('cmf/modal.save') }}</button>
    </div>
</div>
