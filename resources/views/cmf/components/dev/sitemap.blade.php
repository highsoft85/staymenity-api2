@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.sitemap'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Sitemap',
        'description' => '',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="list-group">
                <ul class="list-group">
                    @foreach($oTags as $oTag)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ $oTag->url }}">{{ $oTag->url }}</a>
                            <span>{{ $oTag->lastModificationDate->format('d.m.Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
