<div class="video-list-item-main">
    <div style="position:relative;line-height: 0;">
        <div style="position: relative;" class="thumb-container" @if (!empty($video->preview_url)) data-live-preview="{{ $video->preview_url }}" @else data-live-thumbs="{{ implode(',', (array)$video->other_thumbs_url) }}" @endif data-video-id="{{$video->id}}">
            <div class="live-preview-loader"></div>

            <a href="{{ route('videoView', [$video->id, \App\Utils::videoTitleSlug($video->title)], false) }}">
                <img data-video-thumb src="{{ $video->default_thumb_url }}">
                <div data-video-preview-container style="display: none;"></div>
                <div data-thumb-shadow></div>

                <div class="video-duration">
                    {{ \App\Utils::videoDuration($video->duration) }}
                </div>

                <div data-video-spinner>
                    <div class="video-loading-spinner"></div>
                </div>
            </a>

        </div>
    </div>

    <a href="{{ route('videoView', [$video->id, \App\Utils::videoTitleSlug($video->title)], false) }}" style="text-decoration: none;">
        <div class="title">
            {{ $video->title }}
        </div>
    </a>

    <div class="info-container">
        <div class="views-count">
            {{ \App\Utils::numberShortener($video->views_count) }} views
        </div>

        <div class="votes-percent good">
            {{ round($video->votes_pct) }}%
        </div>
    </div>
</div>
