@if (count($videos))
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
@else
    <div class="alert alert-info" style="text-align: center;margin-top: 20px;">
        You don't have any history of visited videos here.
    </div>
@endif
