@extends('front.layout.default')
@section('title', ucwords($searchInputValue) . ' Porn Videos')

@section('content')

    <div class="container">
        <div class="video-section-title">
            <mark>
                {{ ucwords($searchInputValue) }} Porn Videos
            </mark>
        </div>

        @if (count($videos))
            <div class="videos-section">
                @foreach ($videos as $video)
                    <x-videos-list-item-main :video="$video" />
                @endforeach
            </div>
        @else
            <div class="alert alert-warning" style="display: inline-block;margin-top:20px;line-height: 22px;">
                We are sorry, but we did not have videos based on your query.<br>
                Below, you can find some popular suggestions instead.<br>
                <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Home->name, [], false) }}" style="color: white;text-decoration: underline;">Back to HOME</a>
            </div>

            <div class="videos-section">
                @foreach ($alternatives as $video)
                    <x-videos-list-item-main :video="$video" />
                @endforeach
            </div>
        @endif

        <nav class="navigation pagination">
            <div class="nav-links">
                {{-- Pagination html generated from service class, no user input here --}}
                {!! $pagination !!}
            </div>
        </nav>
    </div>

@endsection
