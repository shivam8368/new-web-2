@extends('front.layout.default')
@section('title',  $categoryName . ' Category')

@section('content')
    <div class="container">

        <div class="video-section-title">
            <mark>
                {{ $categoryName }} Category
            </mark>
        </div>

        <div class="videos-section">
            @foreach ($videos as $video)
                <x-videos-list-item-main :video="$video" />
            @endforeach
        </div>

        <nav class="navigation pagination">
            <div class="nav-links">
                {{-- Pagination html generated from service class, no user input here --}}
                {!! $pagination !!}
            </div>
        </nav>
    </div>
@endsection
