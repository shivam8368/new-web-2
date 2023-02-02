@extends('front.layout.default')
@section('title', 'Recommended Videos')

@section('content')

    <div class="container">

        {{-- Recommended --}}
        <div class="video-section-title">
            <mark>
                Recommended
            </mark>
        </div>

        <div style="font-style: italic;font-weight: 300;margin-top: 14px;">
            @if ($viewedHistoryCount)
                Recommended videos are based on your visited videos history. <a href="{{ route('recommendedReset', [], false) }}" style="font-style: normal;">Reset recommendations</a>
            @else
                Recommended videos were selected randomly because you don't have visited videos history.
            @endif
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
