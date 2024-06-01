<div class="col">
    <ul class="nav nav-tabs" role="tablist">
        @foreach($oOptions as $oOption)
            <li class="nav-item">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab" href="#option_{{ $oOption->name }}" role="tab" aria-controls="option_{{ $oOption->name }}" aria-expanded="true">
                    {{ $oOption->title }}
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($oOptions as $oOption)
            <div class="tab-pane tab-submit {{ $loop->first ? 'active' : '' }}" id="option_{{ $oOption->name }}" role="tabpanel" aria-expanded="true">
                <?php
                    $parameters = $oParameters->where('option_id', $oOption->id);
                ?>
                <div class="row">
                    <div class="col-12">
                        @if(count($parameters) !== 0)
                            @foreach($parameters as $oParameter)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input class="form-control" type="text" value="{{ $oParameter->quantity }}" placeholder="Value">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted">Empty</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

