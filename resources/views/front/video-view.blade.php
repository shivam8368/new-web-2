@extends('front.layout.default')
@section('title', $videoData->title)

@section('content')
    <div class="container" style="padding-top: 30px;">

        <div class="video-view-container">
            <div data-left-side>
                <iframe style="transition: none;" class="main-panel" data-video-iframe src="{{ $videoData->embed_url }}" allowfullscreen scrolling="no" sandbox="allow-scripts allow-same-origin" data-auto-height-ratio="56"></iframe>

                <div class="video-title-container">
                    <div>
                        {{ $videoData->title }}
                    </div>

                    <button class="btn" data-large-panel>Large Player</button>
                </div>

                <div class="video-stats-container">
                    <i data-icon="feather/eye"></i> {{\App\Utils::numberShortener($videoData->views_count)}}

                    <div class="separator">|</div>
                    <i data-icon="feather/star"></i> {{ $videoData->votes_pct }} %

                    <div class="separator">|</div>
                    <i data-icon="feather/thumbs-up" style="color: rgb(169, 255, 169)"></i>
                    <span style="font-weight:400">{{ \App\Utils::numberShortener($videoData->votes_up) }}</span>

                    <div class="separator">|</div>
                    <i data-icon="feather/thumbs-down" style="color: rgb(255, 169, 169);"></i>
                    <span style="font-weight:400">{{ \App\Utils::numberShortener($videoData->votes_down) }}</span>
                </div>

                <div class="video-categories-container">
                    Categories:

                    @foreach ($videoData->categories as $categoryId => $categoryName)
                        <a href="{{ route('categoryView', [$categoryId, \Illuminate\Support\Str::slug($categoryName)], false) }}" class="reset">
                            <div class="category">
                                {{ $categoryName }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div data-right-side>
                <div class="main-panel" style="user-select: none;color: white;font-weight: 300;">
                    <div style="margin-top: 10px;font-size: 20px;text-align: center;line-height: 32px;">
                        You can create your own<br>PORN site like this!
                    </div>

                    <div style="font-size: 18px;text-align: center;margin-top: 28px;line-height: 28px;">
                        It will be done within a minutes<br>and take only a few clicks.
                    </div>

                    <a href="https://adultvideosapi.com" target="_blank" class="ads-button">
                        CHECK IT OUT
                    </a>
                </div>
            </div>
        </div>

        <hr>

        <div class="video-section-title">
            <mark>
                Related videos
            </mark>
        </div>

        <div class="videos-section">
            @foreach (array_slice($relatedVideos, 0, 16) as $video)
                <x-videos-list-item-main :video="$video" />
            @endforeach
        </div>
    </div>

    @if($differentVideoOrientation)
        <div class="orientation-change-box" data-orientation-box>
            <i data-icon="feather/x" class="close-button"></i>

            This video is from different section <div class="text-section"><i data-icon="{{ $differentVideoOrientation['new'] }}"></i>{{ ucfirst($differentVideoOrientation['new']) }}</div>. What do you want to do?

            <div class="buttons-container">
                <div>
                    <div class="button" data-orientation-box-button="">
                        Keep <span>{{ ucfirst($differentVideoOrientation['current']) }}</span>
                    </div>
                </div>

                <div>
                    <div class="button" data-orientation-box-button="{{ $differentVideoOrientation['new'] }}">
                        Change to <span>{{ ucfirst($differentVideoOrientation['new']) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection


@section('footer.js')
    <script>
        let videosHistory = localStorage.getItem('videosHistory') || '';
        if(!videosHistory.startsWith('{{ $videoData->id }},')) {
            if(videosHistory.length > 10000) {
                videosHistory = videosHistory.slice(0, 9000).replace(/\d+$/, '');
            }

            localStorage.setItem('videosHistory', '{{ $videoData->id }},' + videosHistory);
        }
    </script>
@endsection
