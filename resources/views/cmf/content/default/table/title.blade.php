@if(isset($aComposerCmf[$model]['title']))
    <section class="section">
        <h1 class="title">{{ $aComposerCmf[$model]['title'] }}</h1>
        {{--<hr>--}}
        @if(!empty($aComposerCmf[$model]['description']))
            <div class="text-muted page-desc">{{ $aComposerCmf[$model]['description'] }}</div>
        @endif
    </section>
@endif
