@extends('front.layout.default')

@section('content')

    <div class="container">

        {{-- Trending first 8 --}}
        <div style="display: flex;align-items: center;justify-content: space-between">
            <div>
                <div class="video-section-title">
                    <mark>
                        {{ \App\Utils::getVideosOrderName($settingsService->get('videosOrder')) }}
                    </mark>
                </div>
            </div>

            <div>
                <div class="dropdown">
                    <div data-dropdown="order" style="background: #181818;">
                        <div>
                            <i data-icon="sort" style="margin-right: 4px;"></i>

                            {{ \App\Utils::getVideosOrderName($settingsService->get('videosOrder')) }}
                        </div>
                    </div>

                    <div data-dropdown-content="order">
                        <div class="dropdown-content-menu-panel">
                            <div class="item @if (($settingsService->get('videosOrder') ?? \App\Enums\VideosOrderEnum::TRENDING) == \App\Enums\VideosOrderEnum::TRENDING) active @endif" data-href="{{ route('setVideosOrder', [\App\Enums\VideosOrderEnum::TRENDING], false) }}">
                                <div class="text">
                                    Trending
                                </div>
                            </div>

                            <hr style="margin: 4px 0;">

                            <div class="item @if ($settingsService->get('videosOrder') == \App\Enums\VideosOrderEnum::NEWEST) active @endif" data-href="{{ route('setVideosOrder', [\App\Enums\VideosOrderEnum::NEWEST], false) }}">
                                <div class="text">
                                    Newest
                                </div>
                            </div>

                            <hr style="margin: 4px 0;">

                            <div class="item @if ($settingsService->get('videosOrder') == \App\Enums\VideosOrderEnum::VIEWS_COUNT) active @endif" data-href="{{ route('setVideosOrder', [\App\Enums\VideosOrderEnum::VIEWS_COUNT], false) }}">
                                <div class="text">
                                    Most Viewed
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="videos-section">
            @foreach (array_slice($videos['home'], 0, 8) as $video)
                <x-videos-list-item-main :video="$video" />
            @endforeach

        @if (count($videos['recommended']))
        </div>

            {{-- Recommended --}}
            <div class="video-section-title">
                <mark data-href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Recommended->name, [], false) }}">
                    Recommended
                </mark>
            </div>

            <div class="videos-section">
                @foreach (array_slice($videos['recommended'], 0, 8) as $video)
                    <x-videos-list-item-main :video="$video" />
                @endforeach
            </div>

            {{-- Trending rest --}}
            <div class="video-section-title">
                <mark>
                    More from {{ \App\Utils::getVideosOrderName($settingsService->get('videosOrder')) }}
                </mark>
            </div>

        <div class="videos-section">
        @endif

            @foreach (array_slice($videos['home'], 8) as $video)
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
