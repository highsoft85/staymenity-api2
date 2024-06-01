<form class="row ajax-form" action="{{ routeCmf($model.'.action.item.post', ['id' => $oItem->id, 'name' => 'socialsSave']) }}">
    <input type="hidden" name="id" value="{{ $oItem->id }}">
    <div class="col-12">
        @php
        $oSocial = new \App\Models\Social();
        $aSocialTypes = $oSocial->typeIcons;
        @endphp
        @foreach($oSocial->types as $key => $title)
            <div class="form-group">
                <label>{{ $title }}</label>
                <div class="input-group">
                    <div class="input-group-prepend bg-default" style="width: 42px;text-align: center;">
                        <span class="input-group-text">
                            <i class="{{ $aSocialTypes[$key]['class'] }}"></i>
                        </span>
                    </div>
                    <input class="form-control"
                           type="text"
                           name="{{ $key }}"
                           placeholder="{{ $aSocialTypes[$key]['placeholder'] }}"
                           value="{{ $oItem->getSocialValue($key, 'value') }}"
                           @if(isset($aSocialTypes[$key]['options']) && $aSocialTypes[$key]['options']['type'] === 'prefix')
                           data-role="js-mask-instagram"
                           @endif
                           @if(isset($aSocialTypes[$key]['options']) && $aSocialTypes[$key]['options']['type'] === 'phone')
                           data-role="js-mask-phone"
                           @endif
                    >
                </div>
                <div class="input-form-collapse">
                    <div class="__button">
                        <small>
                            <a class="text-black-50" data-toggle="collapse" href="#collapseExample-{{ $key }}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                Add comment
                            </a>
                        </small>
                    </div>
                    <div class="__form collapse" id="collapseExample-{{ $key }}">
                        <label for="{{ $key }}-description">Comment</label>
                        <textarea class="form-control" name="{{ $key }}-description" id="{{ $key }}-description" cols="30" rows="2">{{ $oItem->getSocialValue($key, 'description') }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</form>
