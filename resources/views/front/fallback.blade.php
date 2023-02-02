@extends('front.layout.default')

@section('content')

    <div class="container">
        <div class="video-section-title">
            <mark>
                Ooops :(
            </mark>
        </div>


        <div class="alert alert-warning" style="display: inline-block;margin-top:20px;line-height: 22px;">
            We are sorry, but we can't display this page.

            <div style="margin: 10px 0;font-weight: 600;font-size: 118%;display: flex;align-items: center;">
                <i data-icon="feather/chevron-right"></i>

                <div>
                    <span style="font-weight: 800;">Error:</span> {{ $errorText }}
                </div>
            </div>

            @if (count($videos))
                Below, you can find some popular videos instead.
            @endif

            <div style="margin-top: 10px;">
                <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Home->name, [], false) }}" style="color: white;text-decoration: underline;">Back to HOME</a>
            </div>
        </div>

        <div class="videos-section">
            @foreach ($videos as $video)
                <x-videos-list-item-main :video="$video" />
            @endforeach
        </div>

    </div>

@endsection
