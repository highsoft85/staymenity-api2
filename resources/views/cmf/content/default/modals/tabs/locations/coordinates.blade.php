<form class="row ajax-form --view-coordinates-tabs"
      id="{{ $model }}-coordinates"
      action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'coordinatesSave']) }}"
      data-view=".--view-coordinates-tabs"
      data-callback="replaceView"
      data-ajax-init="coordinates, multiselect"
>
    <input type="hidden" name="id" value="{{ $oItem->id }}">
    <div class="col-md-12 mb-12">
        <ul class="nav nav-tabs is-init" role="tablist">
            @foreach($oItem->locations as $oLocation)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->index === 0 && !isset($lastAdded) ? 'active' : ''}} {{ $loop->last && isset($lastAdded) ? 'active' : ''}}"
                       data-toggle="tab"
                       href="#location-{{ $oLocation->id }}" role="tab" aria-controls="home" aria-expanded="true"
                    >
                        @if(!is_null($oLocation->title))
                            {{ $oLocation->title }}
                        @else
                            # {{ $oLocation->id }}
                        @endif
                    </a>
                </li>
            @endforeach
            <li class="nav-item">
                <button class="btn btn-light nav-link ajax-link" type="button" title="Добавить координаты" action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'coordinatesAddTab']) }}"
                        data-loading="1"
                        data-view=".--view-coordinates-tabs"
                        data-callback="replaceView"
                        data-ajax-init="coordinates, multiselect"
                        style="height: calc(100% - 1px); cursor: pointer;"
                >
                    <i class="fa fa-plus"></i>
                </button>
                {{--                <a class="nav-link ajax-link" role="button" title="Добавить координаты"--}}
                {{--                   action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'coordinatesAddTab']) }}"--}}
                {{--                   data-loading="1"--}}
                {{--                   data-view=".--view-coordinates-tabs"--}}
                {{--                   data-callback="updateView"--}}
                {{--                >--}}
                {{--                    <i class="fa fa-plus"></i>--}}
                {{--                </a>--}}
            </li>
        </ul>
        <div class="tab-content" style="visibility: visible;">
            @foreach($oItem->locations as $oLocation)
                <div class="tab-pane {{ $loop->index === 0 && !isset($lastAdded) ? 'active' : ''}} {{ $loop->last && isset($lastAdded) ? 'active' : ''}}"
                     id="location-{{ $oLocation->id }}" role="tabpanel" aria-expanded="true"
                >
                    @if(config('services.yandex_map.enabled'))
                        @include('cmf.content.default.modals.tabs.locations.tab', [
                            'model' => $model,
                            'oItem' => $oItem,
                            'oLocation' => $oLocation,
                        ])
                    @else
                        <div class="alert alert-danger" role="alert">
                            Yandex maps is <b>disabled</b>.
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</form>
