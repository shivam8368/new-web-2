@extends('front.layout.default')
@section('title', 'Porn Categories')

@section('content')

    <div class="container">
        <div>
            {{-- Trending first 8 --}}
            <div class="category-title-container">
                <div>
                    <div class="video-section-title">
                        <mark>
                            Categories
                        </mark>
                    </div>
                </div>

                <input class="input" placeholder="Search for category ..." data-category-search>
            </div>

            <div class="categories-page-container">
                @foreach ($categories as $category)
                    <div data-category-search-item="{{ strtolower($category->name) }}">
                        <a href="{{ route('categoryView', [$category->id, \Illuminate\Support\Str::slug($category->name)], false) }}">
                            <div class="main-panel">
                                <div class="category-name">{{ $category->name }}</div>

                                <div class="videos-count-container" title="Videos count">
                                    {{ \App\Utils::numberShortener(env('API_ONLY_BEST') ? $category->videos_count_best : $category->videos_count) }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

@endsection
